<?php
// Este arquivo NÃO MOSTRA NADA (só processa dados)
// AGORA ELE PROCESSA TANTO 'novo.php' QUANTO 'editar.php'

// 1. Inclui a lógica de produtos (voltando 2 níveis)
require_once '../../src/products.php';

// 2. Inicia a sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 3. (Segurança) Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// 4. (Segurança) Verifica se os dados vieram via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 5. Pega os dados do formulário
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $id_categoria = (int)$_POST['id_categoria'];
    
    // 6. Pega o ID (se ele existir)
    // (O formulário 'editar.php' manda um <input type="hidden" name="id">)
    $id_produto = null;
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id_produto = (int)$_POST['id'];
    }

    // 7. Validação simples
    if (empty($nome) || empty($id_categoria)) {
        // Se dados obrigatórios faltarem...
        if ($id_produto) {
            header("Location: editar.php?id=" . $id_produto); // Volta pro editar
        } else {
            header("Location: novo.php"); // Volta pro novo
        }
        exit();
    }
    
    // -------------------------------------------------
    // LÓGICA PRINCIPAL: ATUALIZAR ou CADASTRAR?
    // -------------------------------------------------
    
    $sucesso = false;
    
    if ($id_produto) {
        // --- ATUALIZAR ---
        // Se $id_produto NÃO é nulo, significa que é uma ATUALIZAÇÃO
        $sucesso = atualizarProduto($id_produto, $nome, $descricao, $id_categoria);
        
    } else {
        // --- CADASTRAR ---
        // Se $id_produto é nulo, significa que é um NOVO CADASTRO
        $sucesso = cadastrarProduto($nome, $descricao, $id_categoria);
    }
    
    // 8. Redireciona de volta para a lista
    if ($sucesso) {
        header("Location: index.php");
        exit();
    } else {
        echo "Ocorreu um erro ao salvar o produto.";
    }

} else {
    // Se alguém tentar acessar este arquivo direto pela URL (via GET)
    header("Location: index.php");
    exit();
}
?>

