<?php
$page_title = 'Gráfico de Movimentações';
$base_path = '../'; 
$pagina_ativa = 'movimentacao_grafico'; // Novo item de menu

include '../../templates/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); 
    exit(); 
}

require_once '../../src/dashboard_stats.php';

// --- Lógica de Filtro de Datas ---
// Padrão: Últimos 7 dias
$dataFim = date('Y-m-d');
$dataInicio = date('Y-m-d', strtotime('-7 days'));

// Se o usuário enviou o filtro
if (isset($_GET['inicio']) && isset($_GET['fim'])) {
    $dataInicio = $_GET['inicio'];
    $dataFim = $_GET['fim'];
}

// Atalhos de período
$link7dias = "?inicio=" . date('Y-m-d', strtotime('-7 days')) . "&fim=" . date('Y-m-d');
$link30dias = "?inicio=" . date('Y-m-d', strtotime('-30 days')) . "&fim=" . date('Y-m-d');
$linkMesAtual = "?inicio=" . date('Y-m-01') . "&fim=" . date('Y-m-t');

// Busca os dados
$dadosGrafico = buscarDadosGraficoMovimentacao($dataInicio, $dataFim);

// Prepara arrays para o Chart.js (JSON)
$labels = [];
$dataEntradas = [];
$dataSaidas = [];

foreach ($dadosGrafico as $dado) {
    $labels[] = date('d/m', strtotime($dado['data'])); // Formata dia/mês
    $dataEntradas[] = $dado['entradas'];
    $dataSaidas[] = $dado['saidas'];
}
?>

<!-- Carrega Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container my-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6 text-marrom-escuro">
            <i class="fas fa-chart-line me-3"></i>Análise de Movimentação
        </h1>
    </div>

    <!-- Filtros -->
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-body bg-light">
            <form method="GET" action="grafico.php" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="inicio" class="form-label fw-bold text-secondary">Data Início</label>
                    <input type="date" class="form-control" id="inicio" name="inicio" value="<?php echo $dataInicio; ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="fim" class="form-label fw-bold text-secondary">Data Fim</label>
                    <input type="date" class="form-control" id="fim" name="fim" value="<?php echo $dataFim; ?>" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Filtrar
                    </button>
                </div>
                <div class="col-md-4 text-end">
                    <div class="btn-group" role="group">
                        <a href="<?php echo $link7dias; ?>" class="btn btn-outline-secondary btn-sm">7 Dias</a>
                        <a href="<?php echo $link30dias; ?>" class="btn btn-outline-secondary btn-sm">30 Dias</a>
                        <a href="<?php echo $linkMesAtual; ?>" class="btn btn-outline-secondary btn-sm">Este Mês</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Gráfico -->
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body p-4">
            <h5 class="card-title text-center mb-4 text-muted">
                Entradas vs. Saídas (<?php echo date('d/m/Y', strtotime($dataInicio)); ?> a <?php echo date('d/m/Y', strtotime($dataFim)); ?>)
            </h5>
            
            <?php if (empty($labels)): ?>
                <div class="alert alert-info text-center">
                    Nenhuma movimentação encontrada neste período.
                </div>
            <?php else: ?>
                <div style="position: relative; height: 400px; width: 100%;">
                    <canvas id="meuGrafico"></canvas>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    <?php if (!empty($labels)): ?>
    const ctx = document.getElementById('meuGrafico').getContext('2d');
    const meuGrafico = new Chart(ctx, {
        type: 'line', // Pode ser 'bar' se preferir barras
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [
                {
                    label: 'Entradas',
                    data: <?php echo json_encode($dataEntradas); ?>,
                    backgroundColor: 'rgba(221, 107, 32, 0.2)', // Laranja suave
                    borderColor: 'rgba(221, 107, 32, 1)',     // Laranja forte
                    borderWidth: 2,
                    tension: 0.3, // Curva suave na linha
                    fill: true
                },
                {
                    label: 'Saídas',
                    data: <?php echo json_encode($dataSaidas); ?>,
                    backgroundColor: 'rgba(56, 161, 105, 0.2)', // Verde suave
                    borderColor: 'rgba(56, 161, 105, 1)',     // Verde forte
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
    <?php endif; ?>
</script>

<?php
include '../../templates/footer.php';
?>