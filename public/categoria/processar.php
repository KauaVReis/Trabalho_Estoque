<?php
// Arquivo "invisível" que processa os formulários 'novo.php' e 'editar.php'

// 1. Inclui a lógica
require_once '../../src/categories.php';

// 2. Inicia a sessão e verifica segurança
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// 3. Verifica se os dados vieram via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 4. Pega os dados
    $nome = trim($_POST['nome']);
    $id_categoria = (isset($_POST['id']) && !empty($_POST['id'])) ? (int)$_POST['id'] : null;

    // 5. Validação
    if (empty($nome)) {
        if ($id_categoria) {
            header("Location: editar.php?id=" . $id_categoria); // Volta pro editar
        } else {
            header("Location: novo.php"); // Volta pro novo
        }
        exit();
    }
    
    // 6. Lógica: Atualizar ou Cadastrar?
    $sucesso = false;
    
    if ($id_categoria) {
        // --- ATUALIZAR ---
        $sucesso = atualizarCategoria($id_categoria, $nome);
    } else {
        // --- CADASTRAR ---
        $sucesso = cadastrarCategoria($nome);
    }
    
    // 7. Redireciona para a lista
    if ($sucesso) {
        header("Location: index.php");
        exit();
    } else {
        echo "Ocorreu um erro ao salvar a categoria.";
    }

} else {
    // Se acessou via GET, manda embora
    header("Location: index.php");
    exit();
}
?>
