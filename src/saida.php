<?php
/*
 * LÓGICA DE SAÍDA DE ESTOQUE (FEFO - First-Expired, First-Out)
 */

require_once __DIR__ . '/db.php';

/**
 * Lista as últimas saídas para o histórico.
 */
function listarUltimasSaidas($limite = 50) {
    $conn = getDBConnection();
    
    // Ajuste o JOIN conforme sua estrutura de SKU/Produto
    // Aqui, fazemos JOIN com Itens_SKU e depois com Produtos para pegar o nome
    $sql = "SELECT 
                m.id, 
                m.data_movimento, 
                m.quantidade, 
                p.nome AS produto_nome,
                sku.codigo_sku,
                u.nome AS usuario_nome
            FROM Movimentacoes_Estoque m
            JOIN Lotes l ON m.id_lote = l.id
            -- Lógica Híbrida (SKU ou Produto direto)
            LEFT JOIN Itens_SKU sku ON l.id_item_sku = sku.id
            LEFT JOIN Produtos p ON sku.id_produto = p.id
            -- Fallback se não tiver SKU (id_item_sku apontando direto para Produto)
            LEFT JOIN Produtos p_direto ON l.id_item_sku = p_direto.id
            
            LEFT JOIN Usuarios u ON m.id_usuario = u.id
            WHERE m.tipo_movimento = 'saida_venda'
            ORDER BY m.data_movimento DESC
            LIMIT ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $limite);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    
    $saidas = [];
    while ($linha = mysqli_fetch_assoc($resultado)) {
        // Ajuste de nome se vier do fallback
        if (empty($linha['produto_nome'])) {
             // Tenta pegar do p_direto se você adicionar na query, ou deixa genérico
             $linha['produto_nome'] = $linha['produto_nome'] ?? $linha['codigo_sku'] ?? "Produto (Verificar ID)"; 
        }
        $saidas[] = $linha;
    }
    
    mysqli_close($conn);
    return $saidas;
}

/**
 * Realiza a BAIXA no estoque usando lógica FEFO.
 * * @param int $id_item_sku ID do SKU (ou Produto) que está saindo.
 * @param float $qtd_solicitada Quantidade total a retirar.
 * @param int $id_usuario Quem está fazendo a saída.
 * @return array Retorna ['sucesso' => bool, 'mensagem' => string]
 */
function registrarSaidaFEFO($id_item_sku, $qtd_solicitada, $id_usuario) {
    $conn = getDBConnection();
    
    // 1. Buscar saldo total disponível para este SKU
    $sql_saldo = "SELECT SUM(quantidade_atual) as total FROM Lotes WHERE id_item_sku = ?";
    $stmt_saldo = mysqli_prepare($conn, $sql_saldo);
    mysqli_stmt_bind_param($stmt_saldo, "i", $id_item_sku);
    mysqli_stmt_execute($stmt_saldo);
    $res_saldo = mysqli_stmt_get_result($stmt_saldo);
    $row_saldo = mysqli_fetch_assoc($res_saldo);
    $saldo_total = $row_saldo['total'] ?? 0;
    mysqli_stmt_close($stmt_saldo);

    if ($saldo_total < $qtd_solicitada) {
        return ['sucesso' => false, 'mensagem' => "Saldo insuficiente. Disponível: " . number_format($saldo_total, 2)];
    }

    // 2. Buscar Lotes com saldo > 0, ordenados por validade (FEFO)
    // Se a validade for NULL, ordenamos pelo ID (mais antigo primeiro - FIFO)
    $sql_lotes = "SELECT id, quantidade_atual, data_validade 
                  FROM Lotes 
                  WHERE id_item_sku = ? AND quantidade_atual > 0 
                  ORDER BY data_validade ASC, id ASC";
                  
    $stmt_lotes = mysqli_prepare($conn, $sql_lotes);
    mysqli_stmt_bind_param($stmt_lotes, "i", $id_item_sku);
    mysqli_stmt_execute($stmt_lotes);
    $res_lotes = mysqli_stmt_get_result($stmt_lotes);
    
    $lotes = [];
    while ($l = mysqli_fetch_assoc($res_lotes)) {
        $lotes[] = $l;
    }
    mysqli_stmt_close($stmt_lotes);

    // 3. Processar a baixa (Transação)
    mysqli_begin_transaction($conn);
    
    try {
        $qtd_restante = $qtd_solicitada;

        foreach ($lotes as $lote) {
            if ($qtd_restante <= 0) break;

            $id_lote = $lote['id'];
            $qtd_no_lote = $lote['quantidade_atual'];
            
            // Determina quanto tirar deste lote
            $qtd_a_retirar = 0;
            if ($qtd_no_lote >= $qtd_restante) {
                $qtd_a_retirar = $qtd_restante; // Lote tem tudo o que precisamos
            } else {
                $qtd_a_retirar = $qtd_no_lote; // Lote não tem tudo, zera ele e continua
            }

            // Atualiza o Lote (diminui a quantidade atual)
            $nova_qtd = $qtd_no_lote - $qtd_a_retirar;
            $sql_update = "UPDATE Lotes SET quantidade_atual = ? WHERE id = ?";
            $stmt_up = mysqli_prepare($conn, $sql_update);
            mysqli_stmt_bind_param($stmt_up, "di", $nova_qtd, $id_lote);
            mysqli_stmt_execute($stmt_up);
            mysqli_stmt_close($stmt_up);

            // Registra Movimentação 
            // (Saída é registrada como valor positivo na quantidade, o tipo 'saida_venda' indica que saiu)
            $tipo = 'saida_venda';
            $sql_mov = "INSERT INTO Movimentacoes_Estoque (id_lote, id_usuario, tipo_movimento, quantidade, data_movimento) 
                        VALUES (?, ?, ?, ?, NOW())";
            $stmt_mov = mysqli_prepare($conn, $sql_mov);
            mysqli_stmt_bind_param($stmt_mov, "iisd", $id_lote, $id_usuario, $tipo, $qtd_a_retirar);
            mysqli_stmt_execute($stmt_mov);
            mysqli_stmt_close($stmt_mov);

            $qtd_restante -= $qtd_a_retirar;
        }

        mysqli_commit($conn);
        mysqli_close($conn);
        return ['sucesso' => true, 'mensagem' => "Saída registrada com sucesso!"];

    } catch (Exception $e) {
        mysqli_rollback($conn);
        mysqli_close($conn);
        return ['sucesso' => false, 'mensagem' => "Erro no banco: " . $e->getMessage()];
    }
}
?>