<?php
$page_title = 'Controle de Validade';
$base_path = '../'; 
$pagina_ativa = 'validade'; 

include '../../templates/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); 
    exit(); 
}

require_once '../../src/validade.php';

// Filtro opcional (padrão: mostrar todos os lotes com validade)
// Se quiser filtrar, pode passar um valor no GET, ex: ?dias=30
$dias_filtro = isset($_GET['dias']) ? (int)$_GET['dias'] : null;
$lista_lotes = listarLotesPorValidade($dias_filtro);
?>

<div class="container my-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6 text-marrom-escuro">
            <i class="fas fa-calendar-check me-3"></i>Controle de Validade
        </h1>
        
        <!-- Filtros Rápidos -->
        <div class="btn-group">
            <a href="index.php" class="btn btn-outline-secondary <?php echo $dias_filtro === null ? 'active' : ''; ?>">Todos</a>
            <a href="index.php?dias=30" class="btn btn-outline-danger <?php echo $dias_filtro === 30 ? 'active' : ''; ?>">Próximos 30 Dias</a>
            <a href="index.php?dias=90" class="btn btn-outline-warning <?php echo $dias_filtro === 90 ? 'active' : ''; ?>">Próximos 90 Dias</a>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Status</th>
                            <th>Data Validade</th>
                            <th>Produto / SKU</th>
                            <th>Lote Fornecedor</th>
                            <th class="text-center">Saldo</th>
                            <th>Fornecedor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista_lotes as $lote): ?>
                            <?php
                                $data_val = strtotime($lote['data_validade']);
                                $hoje = time();
                                $dias_restantes = ceil(($data_val - $hoje) / (60 * 60 * 24));
                                
                                // Define cor e status
                                $classe_linha = '';
                                $status_badge = '';
                                
                                if ($dias_restantes < 0) {
                                    $classe_linha = 'table-danger'; // Vencido (Vermelho forte)
                                    $status_badge = '<span class="badge bg-dark">VENCIDO</span>';
                                } elseif ($dias_restantes == 0) {
                                    $classe_linha = 'table-danger';
                                    $status_badge = '<span class="badge bg-danger">VENCE HOJE</span>';
                                } elseif ($dias_restantes <= 30) {
                                    $classe_linha = 'table-warning'; // Crítico (Amarelo/Laranja)
                                    $status_badge = '<span class="badge bg-danger text-white">Crítico</span>';
                                } elseif ($dias_restantes <= 90) {
                                    // Atenção (sem cor de fundo na linha, só badge)
                                    $status_badge = '<span class="badge bg-warning text-dark">Atenção</span>';
                                } else {
                                    $status_badge = '<span class="badge bg-success">OK</span>';
                                }
                            ?>
                        <tr class="<?php echo $classe_linha; ?>">
                            <td class="text-center"><?php echo $status_badge; ?></td>
                            <td class="fw-bold">
                                <?php echo date('d/m/Y', $data_val); ?>
                                <div class="small text-muted">
                                    <?php 
                                        if ($dias_restantes < 0) echo abs($dias_restantes) . " dias atrás";
                                        elseif ($dias_restantes == 0) echo "Hoje";
                                        else echo "Faltam $dias_restantes dias";
                                    ?>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo htmlspecialchars($lote['produto_nome']); ?></div>
                                <div class="small text-muted"><?php echo htmlspecialchars($lote['codigo_sku']); ?></div>
                            </td>
                            <td class="small"><?php echo htmlspecialchars($lote['codigo_lote_fornecedor'] ?? '-'); ?></td>
                            <td class="text-center fw-bold">
                                <?php echo number_format($lote['quantidade_atual'], 2, ',', '.'); ?> 
                                <span class="fw-normal small"><?php echo htmlspecialchars($lote['unidade']); ?></span>
                            </td>
                            <td class="small text-muted"><?php echo htmlspecialchars($lote['fornecedor_nome']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($lista_lotes)): ?>
                            <tr><td colspan="6" class="text-center p-4 text-muted">Nenhum lote com validade encontrada para este filtro.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div> 
</div> 

<?php
include '../../templates/footer.php';
?>