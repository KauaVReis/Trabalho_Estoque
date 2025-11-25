<?php
/*
 * LÓGICA DE UNIDADES DE MEDIDA (CRUD)
 */

require_once __DIR__ . '/db.php';

/**
 * Lista TODAS as unidades do banco.
 */
function listarUnidades() {
    $conn = getDBConnection();
    $unidades = [];
    
    $sql = "SELECT * FROM Unidades_Medida ORDER BY nome ASC";
    $resultado = mysqli_query($conn, $sql);
    
    if ($resultado) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $unidades[] = $linha;
        }
    }
    
    mysqli_close($conn);
    return $unidades;
}

/**
 * Busca UMA unidade pelo ID.
 */
function buscarUnidadePorId($id) {
    $conn = getDBConnection();
    
    $sql = "SELECT * FROM Unidades_Medida WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    
    $resultado = mysqli_stmt_get_result($stmt);
    $unidade = mysqli_fetch_assoc($resultado);
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    return $unidade;
}

/**
 * Cadastra nova unidade.
 */
function cadastrarUnidade($nome, $sigla) {
    $conn = getDBConnection();
    $sql = "INSERT INTO Unidades_Medida (nome, sigla) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    mysqli_stmt_bind_param($stmt, "ss", $nome, $sigla);
    $sucesso = mysqli_stmt_execute($stmt);
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    return $sucesso;
}

/**
 * Atualiza unidade existente.
 */
function atualizarUnidade($id, $nome, $sigla) {
    $conn = getDBConnection();
    $sql = "UPDATE Unidades_Medida SET nome = ?, sigla = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    mysqli_stmt_bind_param($stmt, "ssi", $nome, $sigla, $id);
    $sucesso = mysqli_stmt_execute($stmt);
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    return $sucesso;
}

/**
 * Exclui unidade.
 */
function excluirUnidade($id) {
    $conn = getDBConnection();
    $sql = "DELETE FROM Unidades_Medida WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    mysqli_stmt_bind_param($stmt, "i", $id);
    $sucesso = mysqli_stmt_execute($stmt);
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    return $sucesso;
}
?>