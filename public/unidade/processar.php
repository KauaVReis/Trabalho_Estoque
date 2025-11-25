<?php
require_once '../../src/units.php';
if (session_status() == PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id'])) { header("Location: ../index.php"); exit(); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $sigla = trim($_POST['sigla']);
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;

    if (!empty($nome) && !empty($sigla)) {
        if ($id) {
            atualizarUnidade($id, $nome, $sigla);
        } else {
            cadastrarUnidade($nome, $sigla);
        }
        header("Location: index.php");
        exit();
    }
}
header("Location: index.php");
?>