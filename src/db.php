<?php
/*
 * FUNÇÃO DE CONEXÃO REUTILIZÁVEL (VERSÃO SIMPLIFICADA - MySQLi)
 */

// Configurações diretas, como solicitado
define('DB_HOST', '127.0.0.1'); // ou 'localhost'
define('DB_NAME', 'gestao_deposito');
define('DB_USER', 'root');
define('DB_PASS', ''); // Deixe em branco se não tiver senha
define('DB_CHARSET', 'utf8mb4');

/**
 * Cria e retorna uma conexão MySQLi.
 * @return mysqli|null Retorna um objeto MySQLi em caso de sucesso ou null em caso de falha.
 */
function getDBConnection() {
    // Esconde erros de conexão para tratar manualmente
    mysqli_report(MYSQLI_REPORT_OFF);
    
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Verifica a conexão
    if (mysqli_connect_errno()) {
        // Em um projeto real, logar o erro.
        // Por agora, isso ajuda a depurar.
        // Você pode trocar 'throw new \Exception' por 'die()' se preferir
        throw new \Exception("Falha na conexão com o banco: " . mysqli_connect_error());
    }
    
    // Define o charset
    mysqli_set_charset($conn, DB_CHARSET);
    
    return $conn;
}

