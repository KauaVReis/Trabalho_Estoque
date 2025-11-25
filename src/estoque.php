<?php
require_once __DIR__ . '/db.php';

/**
 * Lista o saldo de estoque agrupado por Produto/SKU.
 * Inclui a próxima data de validade e lista de fornecedores.
 */
function listarEstoqueGeral() {
    $conn = getDBConnection();
    
    // Query Poderosa:
    // 1. Soma a quantidade total (SUM)
    // 2. Pega a data de validade MAIS PRÓXIMA (MIN) apenas de lotes com saldo > 0
    // 3. Junta os nomes dos fornecedores em uma string (GROUP_CONCAT)
    
    $sql = "SELECT 
                p.id AS id_produto,
                p.nome AS produto_nome,
                sku.id AS id_sku,
                sku.codigo_sku,
                sku.estoque_minimo,
                u.sigla AS unidade,
                
                SUM(l.quantidade_atual) AS saldo_total,
                
                MIN(CASE WHEN l.quantidade_atual > 0 THEN l.data_validade END) AS proxima_validade,
                
                GROUP_CONCAT(DISTINCT f.razao_social SEPARATOR ', ') AS fornecedores
            
            FROM Itens_SKU sku
            JOIN Produtos p ON sku.id_produto = p.id
            LEFT JOIN Lotes l ON l.id_item_sku = sku.id
            LEFT JOIN Unidades_Medida u ON sku.id_unidade_medida = u.id
            LEFT JOIN Fornecedores f ON l.id_fornecedor = f.id
            
            GROUP BY sku.id
            ORDER BY p.nome ASC, sku.codigo_sku ASC";
            
    $resultado = mysqli_query($conn, $sql);
    
    $estoque = [];
    if ($resultado) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $linha['saldo_total'] = (float)$linha['saldo_total'];
            $estoque[] = $linha;
        }
    }
    
    mysqli_close($conn);
    return $estoque;
}
?>