<?php
require_once __DIR__ . '/db.php';

/**
 * Lista lotes que têm saldo positivo (para serem ajustados).
 * Traz informações detalhadas para o usuário escolher o lote certo.
 */
function listarLotesDisponiveis() {
    $conn = getDBConnection();
    
    $sql = "SELECT 
                l.id,
                l.codigo_lote_fornecedor,
                l.data_validade,
                l.quantidade_atual,
                p.nome AS produto_nome,
                sku.codigo_sku,
                u.sigla AS unidade
            FROM Lotes l
            LEFT JOIN Itens_SKU sku ON l.id_item_sku = sku.id
            LEFT JOIN Produtos p ON sku.id_produto = p.id
            LEFT JOIN Unidades_Medida u ON sku.id_unidade_medida = u.id
            WHERE l.quantidade_atual > 0
            ORDER BY p.nome ASC, l.data_validade ASC";
            
    $resultado = mysqli_query($conn, $sql);
    $lotes = [];
    while ($linha = mysqli_fetch_assoc($resultado)) {
        if (empty($linha['produto_nome'])) {
             $linha['produto_nome'] = "Produto ID " . $linha['id'];
        }
        $lotes[] = $linha;
    }
    
    mysqli_close($conn);
    return $lotes;
}

/**
 * Registra a perda de um lote específico.
 */
function registrarPerda($id_lote, $quantidade, $motivo, $id_usuario) {
    $conn = getDBConnection();
    
    // 1. Verifica se o lote tem saldo suficiente
    $sql_check = "SELECT quantidade_atual FROM Lotes WHERE id = ?";
    $stmt_check = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "i", $id_lote);
    mysqli_stmt_execute($stmt_check);
    $res_check = mysqli_stmt_get_result($stmt_check);
    $lote = mysqli_fetch_assoc($res_check);
    mysqli_stmt_close($stmt_check);

    if (!$lote || $lote['quantidade_atual'] < $quantidade) {
        return false; // Saldo insuficiente ou lote inválido
    }

    mysqli_begin_transaction($conn);
    
    try {
        // 2. Atualiza o saldo do Lote (diminui)
        $nova_qtd = $lote['quantidade_atual'] - $quantidade;
        $sql_up = "UPDATE Lotes SET quantidade_atual = ? WHERE id = ?";
        $stmt_up = mysqli_prepare($conn, $sql_up);
        mysqli_stmt_bind_param($stmt_up, "di", $nova_qtd, $id_lote);
        mysqli_stmt_execute($stmt_up);
        mysqli_stmt_close($stmt_up);

        // 3. Registra a Movimentação (Tipo: 'ajuste_perda')
        // Salvamos a quantidade positiva, o tipo define que é saída.
        $tipo = 'ajuste_perda';
        $sql_mov = "INSERT INTO Movimentacoes_Estoque (id_lote, id_usuario, tipo_movimento, quantidade, data_movimento, observacao) 
                    VALUES (?, ?, ?, ?, NOW(), ?)";
        $stmt_mov = mysqli_prepare($conn, $sql_mov);
        mysqli_stmt_bind_param($stmt_mov, "iisds", $id_lote, $id_usuario, $tipo, $quantidade, $motivo);
        mysqli_stmt_execute($stmt_mov);
        mysqli_stmt_close($stmt_mov);

        mysqli_commit($conn);
        mysqli_close($conn);
        return true;

    } catch (Exception $e) {
        mysqli_rollback($conn);
        mysqli_close($conn);
        return false;
    }
}

/**
 * Lista o histórico de perdas.
 */
function listarHistoricoPerdas($limite = 50) {
    $conn = getDBConnection();
    
    $sql = "SELECT 
                m.id, m.data_movimento, m.quantidade, m.observacao,
                p.nome AS produto_nome,
                sku.codigo_sku,
                u.nome AS usuario_nome
            FROM Movimentacoes_Estoque m
            JOIN Lotes l ON m.id_lote = l.id
            LEFT JOIN Itens_SKU sku ON l.id_item_sku = sku.id
            LEFT JOIN Produtos p ON sku.id_produto = p.id
            LEFT JOIN Usuarios u ON m.id_usuario = u.id
            WHERE m.tipo_movimento = 'ajuste_perda'
            ORDER BY m.data_movimento DESC
            LIMIT ?";
            
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $limite);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    
    $historico = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $historico[] = $row;
    }
    mysqli_close($conn);
    return $historico;
}
?>