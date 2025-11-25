<?php
require_once '../../src/lotes.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // ATUALIZADO: Pega o ID do SKU
    $id_item_sku = (int)$_POST['id_item_sku'];
    $id_fornecedor = (int)$_POST['id_fornecedor'];
    $quantidade = (float)$_POST['quantidade'];
    $validade = $_POST['validade'];
    $preco_custo = !empty($_POST['preco_custo']) ? (float)$_POST['preco_custo'] : 0.0;
    $id_usuario = $_SESSION['user_id'];

    // Validação: Verifica id_item_sku em vez de id_produto
    if ($id_item_sku > 0 && $quantidade > 0 && !empty($validade) && $id_fornecedor > 0) {
        
        // Passa id_item_sku para a função
        $sucesso = registrarEntrada($id_item_sku, $id_fornecedor, $quantidade, $validade, $preco_custo, $id_usuario);
        
        if ($sucesso) {
            header("Location: index.php"); 
            exit();
        } else {
            echo "Erro ao registrar entrada no banco de dados.";
        }
        
    } else {
        echo "Dados inválidos. Verifique se selecionou um Item (SKU) válido.";
    }

} else {
    header("Location: nova.php");
    exit();
}
?>