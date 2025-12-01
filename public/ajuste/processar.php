<?php
require_once '../../src/ajuste.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $id_lote = (int)$_POST['id_lote'];
    $quantidade = (float)$_POST['quantidade'];
    $observacao = trim($_POST['observacao']);
    $id_usuario = $_SESSION['user_id'];

    if ($id_lote > 0 && $quantidade > 0 && !empty($observacao)) {
        
        $sucesso = registrarPerda($id_lote, $quantidade, $observacao, $id_usuario);
        
        if ($sucesso) {
            header("Location: index.php"); 
            exit();
        } else {
            echo "<div style='padding:20px; color:red;'>Erro: Saldo insuficiente ou falha no banco. <a href='novo.php'>Voltar</a></div>";
        }
        
    } else {
        echo "Dados inválidos.";
    }

} else {
    header("Location: novo.php");
    exit();
}
?>