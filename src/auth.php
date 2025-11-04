<?php
/*
 * LÓGICA DE AUTENTICAÇÃO (VERSÃO SUPER SIMPLES E COMENTADA)
 * ATUALIZADO PARA USAR 'login' EM VEZ DE 'email'
 */

// Inclui o arquivo de conexão
require_once __DIR__ . '/db.php';

/**
 * Tenta logar um usuário com base no login e senha.
 * @return bool Retorna true se o login der certo, false se der errado.
 */
function attemptLogin($login, $senha) {
    
    // 1. Pega a conexão com o banco.
    $conn = getDBConnection();
    
    // 2. Prepara a query SQL com um placeholder (?)
    // ATUALIZADO: Busca por 'login' e seleciona 'login' e 'hash_senha'
    $sql = "SELECT id, nome, login, hash_senha FROM Usuarios WHERE login = ? LIMIT 1";
    
    // 3. Prepara a consulta (mysqli_prepare)
    $stmt = mysqli_prepare($conn, $sql);
    
    // 4. Liga (bind) a variável $login ao '?'
    // "s" significa que a variável é uma "string" (texto)
    mysqli_stmt_bind_param($stmt, "s", $login);
    
    // 5. Executa a consulta no banco
    mysqli_stmt_execute($stmt);
    
    // 6. Pega os resultados da consulta
    $resultado = mysqli_stmt_get_result($stmt);
    
    // 7. Transforma o resultado em um array
    $usuario = mysqli_fetch_assoc($resultado);
    
    // 8. Fecha a consulta e a conexão (limpa a memória)
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    // 9. Verifica se encontrou um usuário
    if (!$usuario) {
        return false;
    }
    
    // 10. Verifica se a senha está correta
    if (password_verify($senha, $usuario['hash_senha'])) {
        
        // 11. Senha correta! Inicia a sessão.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Salva os dados do usuário na "mochila" da sessão
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['user_name'] = $usuario['nome'];
        $_SESSION['user_login'] = $usuario['login'];
        
        // Retorna true (login deu certo!)
        return true;
        
    } else {
        // Senha incorreta, retorna false
        return false;
    }
}

