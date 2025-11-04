<?php
// Este arquivo NÃO MOSTRA NADA (só processa dados)

// 1. Inclui a lógica de autenticação
require_once '../src/auth.php';

// 2. Inicia a sessão para poder salvar/ler dados
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 3. Pega os dados do formulário (que vieram via POST)
// ATUALIZADO: De 'email' para 'login'
$login = trim($_POST['login']);
$senha = trim($_POST['senha']);

// 4. Tenta fazer o login usando a função do auth.php
if (attemptLogin($login, $senha)) {
    // Deu certo! Redireciona para o painel principal
    header("Location: dashboard.php");
    exit();
    
} else {
    // Deu errado! Salva uma mensagem de erro na sessão
    $_SESSION['login_error'] = "Login ou senha inválidos. Tente novamente.";
    
    // Redireciona DE VOLTA para a página de login
    header("Location: index.php");
    exit();
}

