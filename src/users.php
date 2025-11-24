<?php
/*
 * LÓGICA DE USUÁRIOS (CRUD)
 * (Versão super simples e comentada)
 */

// Inclui o arquivo de conexão
require_once __DIR__ . '/db.php';

/**
 * Lista TODOS os usuários do banco.
 */
function listarUsuarios() {
    $conn = getDBConnection();
    $usuarios = [];
    
    // Não selecionamos o HASH da senha, por segurança.
    $sql = "SELECT id, nome, login, cargo FROM Usuarios ORDER BY nome ASC";
    $resultado = mysqli_query($conn, $sql);
    
    if ($resultado) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $usuarios[] = $linha;
        }
    }
    
    mysqli_close($conn);
    return $usuarios;
}

/**
 * Busca um usuário específico pelo seu ID.
 */
function buscarUsuarioPorId($id) {
    $conn = getDBConnection();
    
    $sql = "SELECT id, nome, login, cargo FROM Usuarios WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    
    // "i" = $id é um Inteiro
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    
    $resultado = mysqli_stmt_get_result($stmt);
    $usuario = mysqli_fetch_assoc($resultado);
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    return $usuario;
}

/**
 * Cadastra um novo usuário no banco.
 * A senha é OBRIGATÓRIA aqui.
 */
function cadastrarUsuario($nome, $login, $cargo, $senha) {
    $conn = getDBConnection();
    
    // 1. CRIPTOGRAFA a senha (NUNCA guarde senha pura)
    $hash_senha = password_hash($senha, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO Usuarios (nome, login, cargo, hash_senha) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    // "ssss" = 4 strings
    mysqli_stmt_bind_param($stmt, "ssss", $nome, $login, $cargo, $hash_senha);
    
    $sucesso = mysqli_stmt_execute($stmt);
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    return $sucesso;
}

/**
 * Atualiza os dados de um usuário (NÃO mexe na senha).
 */
function atualizarUsuario($id, $nome, $login, $cargo) {
    $conn = getDBConnection();
    
    $sql = "UPDATE Usuarios SET nome = ?, login = ?, cargo = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    // "sssi" = 3 strings e 1 inteiro
    mysqli_stmt_bind_param($stmt, "sssi", $nome, $login, $cargo, $id);
    
    $sucesso = mysqli_stmt_execute($stmt);
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    return $sucesso;
}

/**
 * Atualiza SOMENTE a senha de um usuário.
 */
function atualizarSenhaUsuario($id, $senha) {
    $conn = getDBConnection();

    // Criptografa a nova senha
    $hash_senha = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "UPDATE Usuarios SET hash_senha = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    // "si" = 1 string e 1 inteiro
    mysqli_stmt_bind_param($stmt, "si", $hash_senha, $id);

    $sucesso = mysqli_stmt_execute($stmt);
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    return $sucesso;
}


/**
 * Exclui um usuário do banco.
 * CUIDADO: Não teremos como "desfazer".
 */
function excluirUsuario($id) {
    $conn = getDBConnection();
    
    $sql = "DELETE FROM Usuarios WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    // "i" = $id é um Inteiro
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    $sucesso = mysqli_stmt_execute($stmt);
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    return $sucesso;
}
?>