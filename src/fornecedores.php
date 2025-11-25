<?php
/*
 * LÓGICA DE FORNECEDORES (CRUD)
 * (Versão super simples e comentada)
 */

// Inclui o arquivo de conexão
require_once __DIR__ . '/db.php';

/**
 * Lista TODOS os fornecedores do banco.
 */
function listarFornecedores() {
    $conn = getDBConnection();
    $fornecedores = [];
    
    $sql = "SELECT id, razao_social, cnpj, contato_nome, contato_telefone FROM Fornecedores ORDER BY razao_social ASC";
    $resultado = mysqli_query($conn, $sql);
    
    if ($resultado) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $fornecedores[] = $linha;
        }
    }
    
    mysqli_close($conn);
    return $fornecedores;
}

/**
 * Busca UM fornecedor pelo seu ID.
 */
function buscarFornecedorPorId($id) {
    $conn = getDBConnection();
    
    $sql = "SELECT * FROM Fornecedores WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    
    // "i" = $id é um Inteiro
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    
    $resultado = mysqli_stmt_get_result($stmt);
    $fornecedor = mysqli_fetch_assoc($resultado);
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    return $fornecedor; 
}

/**
 * Salva um novo fornecedor no banco.
 */
function cadastrarFornecedor($razao_social, $cnpj, $contato_nome, $contato_telefone) {
    $conn = getDBConnection();
    $sql = "INSERT INTO Fornecedores (razao_social, cnpj, contato_nome, contato_telefone) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    // "ssss" = String, String, String, String
    mysqli_stmt_bind_param($stmt, "ssss", $razao_social, $cnpj, $contato_nome, $contato_telefone);
    
    $sucesso = mysqli_stmt_execute($stmt);
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    return $sucesso;
}

/**
 * ATUALIZA um fornecedor existente.
 */
function atualizarFornecedor($id, $razao_social, $cnpj, $contato_nome, $contato_telefone) {
    $conn = getDBConnection();
    
    $sql = "UPDATE Fornecedores SET razao_social = ?, cnpj = ?, contato_nome = ?, contato_telefone = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    // "ssssi" = String, String, String, String, Integer
    mysqli_stmt_bind_param($stmt, "ssssi", $razao_social, $cnpj, $contato_nome, $contato_telefone, $id);
    
    $sucesso = mysqli_stmt_execute($stmt);
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    return $sucesso;
}

/**
 * EXCLUI um fornecedor do banco.
 */
function excluirFornecedor($id) {
    $conn = getDBConnection();
    
    // ATENÇÃO: Se este fornecedor estiver sendo usado por um LOTE,
    // o lote ficará com 'id_fornecedor = NULL' (definido no schema).
    $sql = "DELETE FROM Fornecedores WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    // "i" = $id é um Integer
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    $sucesso = mysqli_stmt_execute($stmt);
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    return $sucesso;
}
?>