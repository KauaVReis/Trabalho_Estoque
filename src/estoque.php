<?php
require_once __DIR__ . '/db.php';

/**
 * Lista o saldo de estoque agrupado por Produto/SKU.
 * Separa saldo válido de saldo vencido.
 */
function listarEstoqueGeral() {
    $conn = getDBConnection();
    
    // Query Poderosa V2:
    // 1. saldo_valido: Soma apenas lotes onde validade >= Hoje (ou nula)
    // 2. saldo_vencido: Soma apenas lotes onde validade < Hoje
    // 3. proxima_validade: A menor data (MIN) dentre os lotes VÁLIDOS
    // 4. vencimento_recente: A maior data (MAX) dentre os lotes VENCIDOS (o que venceu por último)
    
    $sql = "SELECT 
                p.id AS id_produto,
                p.nome AS produto_nome,
                sku.id AS id_sku,
                sku.codigo_sku,
                sku.estoque_minimo,
                u.sigla AS unidade,
                
                -- Soma apenas o que NÃO venceu
                SUM(CASE 
                    WHEN l.data_validade >= CURDATE() OR l.data_validade IS NULL 
                    THEN l.quantidade_atual 
                    ELSE 0 
                END) AS saldo_valido,
                
                -- Soma apenas o que JÁ venceu
                SUM(CASE 
                    WHEN l.data_validade < CURDATE() 
                    THEN l.quantidade_atual 
                    ELSE 0 
                END) AS saldo_vencido,
                
                -- Pega a próxima validade apenas dos itens VÁLIDOS
                MIN(CASE 
                    WHEN l.quantidade_atual > 0 AND (l.data_validade >= CURDATE() OR l.data_validade IS NULL)
                    THEN l.data_validade 
                END) AS proxima_validade,

                -- Pega a data do item vencido mais recente (para alerta)
                MAX(CASE 
                    WHEN l.quantidade_atual > 0 AND l.data_validade < CURDATE()
                    THEN l.data_validade 
                END) AS data_vencido_recente,
                
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
            $linha['saldo_valido'] = (float)$linha['saldo_valido'];
            $linha['saldo_vencido'] = (float)$linha['saldo_vencido'];
            $estoque[] = $linha;
        }
    }
    
    mysqli_close($conn);
    return $estoque;
}
?>