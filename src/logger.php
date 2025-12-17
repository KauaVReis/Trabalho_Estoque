<?php
require_once __DIR__ . '/db.php';

/**
 * Registra uma ação no log do sistema.
 * 
 * @param string $acao Descrição curta da ação (ex: "Login Sucesso", "Novo Produto")
 * @param string|array|null $detalhes Detalhes adicionais (será convertido para JSON se for array)
 * @param int|null $id_usuario ID do usuário que realizou a ação (se null, tenta pegar da sessão)
 * @return bool True se gravou, False se falhou
 */
function registrarLog($acao, $detalhes = null, $id_usuario = null) {
    // Tenta pegar ID da sessão se não foi passado
    if ($id_usuario === null && isset($_SESSION['user_id'])) {
        $id_usuario = $_SESSION['user_id'];
    }

    // Se detalhes for array, vira JSON
    if (is_array($detalhes)) {
        $detalhes = json_encode($detalhes, JSON_UNESCAPED_UNICODE);
    }

    // Pega IP
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';

    $conn = getDBConnection();
    
    $sql = "INSERT INTO Logs (id_usuario, acao, detalhes, ip) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    // i = int, s = string, s = string, s = string
    mysqli_stmt_bind_param($stmt, "isss", $id_usuario, $acao, $detalhes, $ip);
    
    $sucesso = mysqli_stmt_execute($stmt);
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    return $sucesso;
}

/**
 * Busca logs recentes para exibição.
 */
function listarLogs($limite = 50) {
    $conn = getDBConnection();
    
    $sql = "SELECT l.*, u.nome as usuario_nome, u.login as usuario_login 
            FROM Logs l
            LEFT JOIN Usuarios u ON l.id_usuario = u.id
            ORDER BY l.data_hora DESC 
            LIMIT ?";
            
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $limite);
    mysqli_stmt_execute($stmt);
    
    $res = mysqli_stmt_get_result($stmt);
    
    $logs = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $logs[] = $row;
    }
    
    mysqli_close($conn);
    return $logs;
}
?>
