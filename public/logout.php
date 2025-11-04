<?php
// Este arquivo NÃO MOSTRA NADA (só processa)

// 1. Inicia a sessão para poder "mexer" nela
session_start();

// 2. Destrói todas as variáveis da sessão (limpa a "mochila")
session_unset();

// 3. Destrói a sessão em si
session_destroy();

// 4. Redireciona o usuário de volta para a página de login
header("Location: index.php");
exit();
?>

