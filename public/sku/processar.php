<?php
require_once '../../src/skus.php';
if (session_status() == PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id'])) { header("Location: ../index.php"); exit(); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_produto = (int)$_POST['id_produto'];
    $id_unidade = (int)$_POST['id_unidade'];
    $codigo = trim($_POST['codigo_sku']);
    $estoque_min = !empty($_POST['estoque_minimo']) ? (float)$_POST['estoque_minimo'] : 0;
    $peso = !empty($_POST['peso_bruto_kg']) ? (float)$_POST['peso_bruto_kg'] : 0;
    
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;

    if ($id_produto > 0 && $id_unidade > 0 && !empty($codigo)) {
        if ($id) {
            atualizarSku($id, $id_produto, $id_unidade, $codigo, $estoque_min, $peso);
        } else {
            cadastrarSku($id_produto, $id_unidade, $codigo, $estoque_min, $peso);
        }
        header("Location: index.php");
        exit();
    } else {
        echo "Dados inválidos.";
    }
}
header("Location: index.php");
?>