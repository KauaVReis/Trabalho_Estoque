<?php
// Arquivo "invisível" que processa os formulários 'novo.php' e 'editar.php'

// 1. Inclui a lógica
require_once '../../src/fornecedores.php';

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
    $razao_social = trim($_POST['razao_social']);
    $cnpj = trim($_POST['cnpj']);
    $contato_nome = trim($_POST['contato_nome']);
    $contato_telefone = trim($_POST['contato_telefone']);
    
    $id_fornecedor = (isset($_POST['id']) && !empty($_POST['id'])) ? (int)$_POST['id'] : null;

    // 5. Validação (só a razão social é obrigatória)
    if (empty($razao_social)) {
        if ($id_fornecedor) {
            header("Location: editar.php?id=" . $id_fornecedor); // Volta pro editar
        } else {
            header("Location: novo.php"); // Volta pro novo
        }
        exit();
    }
    
    // 6. Lógica: Atualizar ou Cadastrar?
    $sucesso = false;
    
    if ($id_fornecedor) {
        // --- ATUALIZAR ---
        $sucesso = atualizarFornecedor($id_fornecedor, $razao_social, $cnpj, $contato_nome, $contato_telefone);
    } else {
        // --- CADASTRAR ---
        $sucesso = cadastrarFornecedor($razao_social, $cnpj, $contato_nome, $contato_telefone);
    }
    
    // 7. Redireciona para a lista
    if ($sucesso) {
        header("Location: index.php");
        exit();
    } else {
        echo "Ocorreu um erro ao salvar o fornecedor.";
    }

} else {
    // Se acessou via GET, manda embora
    header("Location: index.php");
    exit();
}
?>