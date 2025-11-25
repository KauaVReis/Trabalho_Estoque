<?php
/*
 * LÓGICA DE LOTES E MOVIMENTAÇÕES (ENTRADA DE ESTOQUE)
 * ATUALIZADO: Correção Robusta para Data de Validade
 */

require_once __DIR__ . '/db.php';

/**
 * Lista as últimas movimentações de entrada (para o histórico).
 */
function listarUltimasEntradas($limite = 50) {
    $conn = getDBConnection();
    
    $sql = "SELECT 
                m.id, 
                m.data_movimento, 
                m.quantidade, 
                p.nome AS produto_nome,
                sku.codigo_sku,
                l.data_validade,
                u.nome AS usuario_nome,
                f.razao_social AS fornecedor_nome
            FROM Movimentacoes_Estoque m
            JOIN Lotes l ON m.id_lote = l.id
            LEFT JOIN Itens_SKU sku ON l.id_item_sku = sku.id
            LEFT JOIN Produtos p ON sku.id_produto = p.id
            LEFT JOIN Usuarios u ON m.id_usuario = u.id
            LEFT JOIN Fornecedores f ON l.id_fornecedor = f.id
            WHERE m.tipo_movimento = 'entrada_compra'
            ORDER BY m.data_movimento DESC
            LIMIT ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $limite);
    mysqli_stmt_execute($stmt);
    
    $resultado = mysqli_stmt_get_result($stmt);
    $entradas = [];
    while ($linha = mysqli_fetch_assoc($resultado)) {
        if (empty($linha['produto_nome']) && empty($linha['codigo_sku'])) {
             $linha['produto_nome'] = "Produto/SKU não encontrado (ID: " . $linha['id'] . ")";
        }
        $entradas[] = $linha;
    }
    
    mysqli_close($conn);
    return $entradas;
}

/**
 * Registra uma NOVA ENTRADA de estoque.
 */
function registrarEntrada($id_item_sku, $id_fornecedor, $quantidade, $validade, $preco_custo, $id_usuario) {
    $conn = getDBConnection();
    
    mysqli_begin_transaction($conn);
    
    try {
        // --- AJUSTE DE DATA SIMPLIFICADO ---
        // Se vier vazia (""), passa NULL.
        // Se vier preenchida ("2025-11-26"), passa a string direto.
        $data_val_final = !empty($validade) ? $validade : null;
        
        // 1. INSERIR O LOTE
        // Voltamos para o Prepared Statement (Segurança)
        $sql_lote = "INSERT INTO Lotes (id_item_sku, id_fornecedor, quantidade_inicial, quantidade_atual, data_validade, custo_compra_unidade, data_entrada) 
                     VALUES (?, ?, ?, ?, ?, ?, NOW())";
                     
        $stmt_lote = mysqli_prepare($conn, $sql_lote);
        
        // Bind dos parâmetros
        // i = integer, d = double/decimal, s = string
        // A data é passada como 's' (string) ou null
        mysqli_stmt_bind_param($stmt_lote, "iiddss", $id_item_sku, $id_fornecedor, $quantidade, $quantidade, $data_val_final, $preco_custo);
        
        if (!mysqli_stmt_execute($stmt_lote)) {
            throw new Exception("Erro ao inserir lote: " . mysqli_stmt_error($stmt_lote));
        }
        
        $id_lote_gerado = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt_lote);

        // 2. INSERIR A MOVIMENTAÇÃO
        $tipo = 'entrada_compra';
        $sql_mov = "INSERT INTO Movimentacoes_Estoque (id_lote, id_usuario, tipo_movimento, quantidade, data_movimento) 
                    VALUES (?, ?, ?, ?, NOW())";
                    
        $stmt_mov = mysqli_prepare($conn, $sql_mov);
        mysqli_stmt_bind_param($stmt_mov, "iisd", $id_lote_gerado, $id_usuario, $tipo, $quantidade);
        
        if (!mysqli_stmt_execute($stmt_mov)) {
            throw new Exception("Erro ao inserir movimentação: " . mysqli_stmt_error($stmt_mov));
        }
        
        mysqli_stmt_close($stmt_mov);

        mysqli_commit($conn);
        mysqli_close($conn);
        return true;

    } catch (Exception $e) {
        mysqli_rollback($conn);
        mysqli_close($conn);
        // Para debug:
        // echo "Erro: " . $e->getMessage();
        return false;
    }
}
?>