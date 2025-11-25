<?php
require_once __DIR__ . '/db.php';

/**
 * Busca lotes ordenados pela validade (mais próximos primeiro).
 * Pode filtrar por dias (ex: próximos 30 dias).
 */
function listarLotesPorValidade($dias = null) {
    $conn = getDBConnection();
    $sql = "SELECT 
                l.id,
                l.codigo_lote_fornecedor,
                l.data_validade,
                l.quantidade_atual,
                p.nome AS produto_nome,
                sku.codigo_sku,
                f.razao_social AS fornecedor_nome,
                u.sigla AS unidade
            FROM Lotes l
            LEFT JOIN Itens_SKU sku ON l.id_item_sku = sku.id
            LEFT JOIN Produtos p ON sku.id_produto = p.id
            LEFT JOIN Produtos p_direto ON l.id_item_sku = p_direto.id
            LEFT JOIN Fornecedores f ON l.id_fornecedor = f.id
            LEFT JOIN Unidades_Medida u ON sku.id_unidade_medida = u.id
            
            WHERE l.quantidade_atual > 0 
            AND l.data_validade IS NOT NULL ";

    if ($dias !== null) {
        // Filtra para mostrar apenas os que vencem nos próximos X dias (ou já venceram)
        $sql .= " AND l.data_validade <= DATE_ADD(CURDATE(), INTERVAL ? DAY)";
    }
    
    $sql .= " ORDER BY l.data_validade ASC"; // Do mais urgente para o menos urgente

    // echo($sql);
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($dias !== null) {
        mysqli_stmt_bind_param($stmt, "i", $dias);
    }
    
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    
    $lotes = [];
    while ($linha = mysqli_fetch_assoc($resultado)) {
        // Normaliza nome do produto
        if (empty($linha['produto_nome'])) {
             $linha['produto_nome'] = $linha['produto_nome'] ?? "Produto ID " . $linha['id']; 
        }
        $lotes[] = $linha;
    }
    
    mysqli_close($conn);
    return $lotes;
}
?>