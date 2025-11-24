<?php
/*
 * PROCESSADOR DE USUÁRIOS (SÓ LÓGICA, SEM HTML)
 */

// 1. SESSÃO E SEGURANÇA (SÓ ADMIN)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['user_cargo'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit();
}

// 2. Verifica se é um POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

// 3. Inclui a lógica
require_once '../../src/users.php';

// 4. Pega a Ação (cadastrar ou atualizar)
$acao = $_POST['acao'];

// 5. Executa a ação
switch ($acao) {
    
    // --- AÇÃO CADASTRAR ---
    case 'cadastrar':
        $nome = $_POST['nome'];
        $login = $_POST['login'];
        $cargo = $_POST['cargo'];
        $senha = $_POST['senha'];

        // Validação simples (só verifica se a senha não está vazia)
        if (!empty($nome) && !empty($login) && !empty($cargo) && !empty($senha)) {
            cadastrarUsuario($nome, $login, $cargo, $senha);
        }
        break;

    // --- AÇÃO ATUALIZAR ---
    case 'atualizar':
        $id = (int)$_POST['id'];
        $nome = $_POST['nome'];
        $login = $_POST['login'];
        $cargo = $_POST['cargo'];
        $senha = $_POST['senha']; // Senha é opcional

        // Validação simples
        if ($id > 0 && !empty($nome) && !empty($login) && !empty($cargo)) {
            
            // 1. Atualiza os dados principais (sempre)
            atualizarUsuario($id, $nome, $login, $cargo);
            
            // 2. Atualiza a senha SOMENTE se ela foi preenchida
            if (!empty($senha)) {
                atualizarSenhaUsuario($id, $senha);
            }
        }
        break;
}

// 6. Redireciona de volta para a lista em qualquer caso
header("Location: index.php");
exit();
?>