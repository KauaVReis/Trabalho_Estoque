<?php
require_once __DIR__ . '/db.php';

/**
 * Lista todos os SKUs para exibição na tabela.
 */
function listarSkus() {
    $conn = getDBConnection();
    
    $sql = "SELECT 
                s.id, 
                s.codigo_sku, 
                s.estoque_minimo,
                s.peso_bruto_kg,
                p.nome AS produto_nome,
                c.nome AS categoria_nome,
                u.sigla AS unidade_sigla
            FROM Itens_SKU s
            JOIN Produtos p ON s.id_produto = p.id
            JOIN Categorias c ON p.id_categoria = c.id
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

/**
 * Busca um SKU específico pelo ID.
 */
function buscarSkuPorId($id) {
    $conn = getDBConnection();
    $sql = "SELECT * FROM Itens_SKU WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $sku = mysqli_fetch_assoc($resultado);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $sku;
}

/**
 * Cadastra um novo SKU.
 */
function cadastrarSku($id_produto, $id_unidade, $codigo, $estoque_min, $peso) {
    $conn = getDBConnection();
    $sql = "INSERT INTO Itens_SKU (id_produto, id_unidade_medida, codigo_sku, estoque_minimo, peso_bruto_kg) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iisdd", $id_produto, $id_unidade, $codigo, $estoque_min, $peso);
    $sucesso = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $sucesso;
}

/**
 * Atualiza um SKU existente.
 */
function atualizarSku($id, $id_produto, $id_unidade, $codigo, $estoque_min, $peso) {
    $conn = getDBConnection();
    $sql = "UPDATE Itens_SKU SET id_produto=?, id_unidade_medida=?, codigo_sku=?, estoque_minimo=?, peso_bruto_kg=? 
            WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iisddi", $id_produto, $id_unidade, $codigo, $estoque_min, $peso, $id);
    $sucesso = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $sucesso;
}

/**
 * Exclui um SKU.
 */
function excluirSku($id) {
    $conn = getDBConnection();
    // Nota: Se houver Lotes ligados a este SKU, o banco pode bloquear (dependendo da FK).
    $sql = "DELETE FROM Itens_SKU WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    $sucesso = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $sucesso;
}

/**
 * Lista simplificada para preencher combobox (select) em outras telas.
 */
function listarSkusParaSelect() {
    $conn = getDBConnection();
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