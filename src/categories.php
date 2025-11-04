<?php
/*
 * LÓGICA DE CATEGORIAS (VERSÃO SUPER SIMPLES E COMENTADA)
 * Funções para Cadastrar, Listar, Buscar, Atualizar e Excluir Categorias.
 */

// Inclui o arquivo de conexão
require_once __DIR__ . '/db.php';

/**
 * Busca TODAS as categorias do banco de dados.
 */
function listarCategorias() {
    $conn = getDBConnection();
    $sql = "SELECT * FROM Categorias ORDER BY nome ASC";
    $resultado = mysqli_query($conn, $sql);
    
    $categorias = [];
    while ($linha = mysqli_fetch_assoc($resultado)) {
        $categorias[] = $linha; 
    }
    
    mysqli_close($conn);
    return $categorias;
}

/**
 * Busca UMA ÚNICA categoria pelo seu ID.
 * (Usado na página 'editar.php' para preencher o formulário)
 */
function buscarCategoriaPorId($id) {
    $conn = getDBConnection();
    
    $sql = "SELECT * FROM Categorias WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    
    // "i" = $id é um Integer
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    
    $resultado = mysqli_stmt_get_result($stmt);
    $categoria = mysqli_fetch_assoc($resultado);
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    return $categoria; // Retorna a categoria (ou null se não achou)
}

/**
 * Salva uma nova categoria no banco de dados.
 */
function cadastrarCategoria($nome) {
    $conn = getDBConnection();
    $sql = "INSERT INTO Categorias (nome) VALUES (?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    // "s" = $nome é uma String
    mysqli_stmt_bind_param($stmt, "s", $nome);
    
    $sucesso = mysqli_stmt_execute($stmt);
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    return $sucesso;
}

/**
 * ATUALIZA uma categoria existente no banco.
 */
function atualizarCategoria($id, $nome) {
    $conn = getDBConnection();
    
    $sql = "UPDATE Categorias SET nome = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    // "si" = String, Integer
    mysqli_stmt_bind_param($stmt, "si", $nome, $id);
    
    $sucesso = mysqli_stmt_execute($stmt);
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    return $sucesso;
}

/**
 * EXCLUI uma categoria do banco de dados.
 */
function excluirCategoria($id) {
    $conn = getDBConnection();
    
    // ATENÇÃO: Se esta categoria estiver sendo usada por um PRODUTO,
    // o banco de dados pode (e deve) bloquear a exclusão para
    // manter a integridade dos dados.
    $sql = "DELETE FROM Categorias WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    // "i" = $id é um Integer
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    $sucesso = mysqli_stmt_execute($stmt);
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    return $sucesso;
}
?>
