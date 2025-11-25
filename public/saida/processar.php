<?php
require_once '../../src/saida.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $id_item_sku = (int)$_POST['id_item_sku'];
    $quantidade = (float)$_POST['quantidade'];
    $id_usuario = $_SESSION['user_id'];

    if ($id_item_sku > 0 && $quantidade > 0) {
        
        // Chama a função mágica FEFO
        $resultado = registrarSaidaFEFO($id_item_sku, $quantidade, $id_usuario);
        
        if ($resultado['sucesso']) {
            // Sucesso! Vai para a lista de saídas (histórico)
            header("Location: index.php"); 
            exit();
        } else {
            // Erro (Ex: Saldo insuficiente)
            // Idealmente, usaríamos sessões para passar mensagens de erro.
            // Para simplificar, mostramos uma página de erro simples.
            echo "<div style='padding:20px; color:red; font-family:sans-serif;'>";
            echo "<h1>Erro na Saída</h1>";
            echo "<p>" . htmlspecialchars($resultado['mensagem']) . "</p>";
            echo "<a href='nova.php'>Voltar e tentar novamente</a>";
            echo "</div>";
        }
        
    } else {
        echo "Dados inválidos.";
    }

} else {
    header("Location: nova.php");
    exit();
}
?>