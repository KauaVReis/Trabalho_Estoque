<?php
require_once __DIR__ . '/db.php';

/**
 * Lista todos os SKUs com detalhes do produto pai.
 * Usado para preencher selects em formulários.
 */
function listarSkusParaSelect() {
    $conn = getDBConnection();
    
    // Busca SKU + Nome do Produto + Unidade de Medida
    $sql = "SELECT 
                s.id, 
                s.codigo_sku, 
                p.nome AS produto_nome,
                u.sigla AS unidade_sigla
            FROM Itens_SKU s
            JOIN Produtos p ON s.id_produto = p.id
            LEFT JOIN Unidades_Medida u ON s.id_unidade_medida = u.id
            ORDER BY p.nome ASC, s.codigo_sku ASC";
            
    $resultado = mysqli_query($conn, $sql);
    
    $skus = [];
    if ($resultado) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $skus[] = $linha;
        }
    }
    
    mysqli_close($conn);
    return $skus;
}
?>