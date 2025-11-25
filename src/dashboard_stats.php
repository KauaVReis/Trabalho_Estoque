<?php
require_once __DIR__ . '/db.php';

/**
 * Conta quantos lotes vão vencer nos próximos X dias.
 */
function contarLotesVencendo($dias = 30) {
    $conn = getDBConnection();
    
    // Lógica: Data de Validade >= Hoje E Data de Validade <= Hoje + Dias
    // E também deve ter saldo > 0 (lotes vazios não importam)
    $sql = "SELECT COUNT(*) as total FROM Lotes 
            WHERE quantidade_atual > 0 
            AND data_validade BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY)";
            
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $dias);
    mysqli_stmt_execute($stmt);
    
    $resultado = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($resultado);
    
    mysqli_close($conn);
    return $row['total'] ?? 0;
}

/**
 * Conta quantos SKUs estão com estoque total abaixo do mínimo.
 */
function contarEstoqueBaixo() {
    $conn = getDBConnection();
    
    // Essa query é um pouco mais complexa:
    // 1. Agrupa lotes por SKU e soma a quantidade atual.
    // 2. Compara essa soma com o estoque_minimo do SKU.
    // 3. Conta quantos SKUs satisfazem a condição (Soma <= Minimo).
    
    // NOTA: Usamos uma subquery ou HAVING para filtrar.
    // Vamos listar todos e filtrar no PHP ou fazer direto no SQL.
    // Direto no SQL é mais performático:
    
    $sql = "SELECT COUNT(*) as total FROM (
                SELECT 
                    sku.id, 
                    sku.estoque_minimo,
                    IFNULL(SUM(l.quantidade_atual), 0) as saldo_total
                FROM Itens_SKU sku
                LEFT JOIN Lotes l ON l.id_item_sku = sku.id
                GROUP BY sku.id
                HAVING saldo_total <= sku.estoque_minimo
            ) as subquery";
            
    $resultado = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($resultado);
    
    mysqli_close($conn);
    return $row['total'] ?? 0;
}

/**
 * Conta movimentações recentes (Entradas/Saídas) hoje.
 * Opcional, mas legal para o dashboard.
 */
function contarMovimentacoesHoje() {
    $conn = getDBConnection();
    
    $sql = "SELECT 
                SUM(CASE WHEN tipo_movimento = 'entrada_compra' THEN 1 ELSE 0 END) as entradas,
                SUM(CASE WHEN tipo_movimento = 'saida_venda' THEN 1 ELSE 0 END) as saidas
            FROM Movimentacoes_Estoque 
            WHERE DATE(data_movimento) = CURDATE()";
            
    $resultado = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($resultado);
    
    mysqli_close($conn);
    return $row; // Retorna array ['entradas' => X, 'saidas' => Y]
}
?>