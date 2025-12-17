<?php
/*
 * LÓGICA DE AUTENTICAÇÃO (VERSÃO SUPER SIMPLES E COMENTADA)
 * * ATUALIZAÇÃO: Agora também armazena o NOME e o CARGO do usuário na sessão.
 */

// Inclui o arquivo de conexão
require_once __DIR__ . '/db.php';

/**
 * Tenta logar um usuário.
 * Retorna true se deu certo, false se falhou.
 */
function attemptLogin($login, $senha) {
    $conn = getDBConnection();
    
    // 1. Busca o usuário pelo login
    $sql = "SELECT id, nome, hash_senha, cargo FROM Usuarios WHERE login = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    
    // "s" = $login é uma String
    mysqli_stmt_bind_param($stmt, "s", $login);
    mysqli_stmt_execute($stmt);
    
    $resultado = mysqli_stmt_get_result($stmt);
    $usuario = mysqli_fetch_assoc($resultado);
    
    $sucesso = false; // Começa como falso
    
    // 2. Verifica se achou o usuário E se a senha bate
    if ($usuario && password_verify($senha, $usuario['hash_senha'])) {
        
        // 3. Sucesso! Guarda os dados na Sessão
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['user_name'] = $usuario['nome']; // <-- NOVO
        $_SESSION['user_cargo'] = $usuario['cargo']; // <-- NOVO E IMPORTANTE
        
        // Log de Sucesso
        require_once __DIR__ . '/logger.php';
        registrarLog('Login Sucesso', null, $usuario['id']);

        $sucesso = true;
    } else {
        // Log de Falha (Tentativa)
        // Se achou o usuário mas errou a senha, loga o ID. Se não, loga null.
        require_once __DIR__ . '/logger.php';
        $id_tentativa = $usuario ? $usuario['id'] : null;
        registrarLog('Login Falha', ['login_tentado' => $login], $id_tentativa);
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    return $sucesso;
}
?>