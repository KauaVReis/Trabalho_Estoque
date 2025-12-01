<?php
$page_title = 'Histórico de Perdas';
$base_path = '../'; 
$pagina_ativa = 'ajuste'; 

include '../../templates/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); 
    exit(); 
}

require_once '../../src/ajuste.php';
$historico = listarHistoricoPerdas();
?>

<div class="container my-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6 text-marrom-escuro">
            <i class="fas fa-clipboard-list me-3"></i>Histórico de Perdas
        </h1>
        <a href="novo.php" class="btn btn-lg btn-danger">
            <i class="fas fa-trash-alt me-2"></i>Registrar Perda
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
            
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Data</th>
                            <th>Produto</th>
                            <th>Qtd Perdida</th>
                            <th>Motivo</th>
                            <th>Responsável</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historico as $h): ?>
                        <tr>
                            <td><?php echo date('d/m/Y H:i', strtotime($h['data_movimento'])); ?></td>
                            <td class="fw-bold">
                                <?php echo htmlspecialchars($h['produto_nome']); ?>
                                <small class="text-muted ms-2"><?php echo htmlspecialchars($h['codigo_sku']); ?></small>
                            </td>
                            <td class="text-danger fw-bold">-<?php echo number_format($h['quantidade'], 2); ?></td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <?php echo htmlspecialchars($h['observacao']); ?>
                                </span>
                            </td>
                            <td class="text-muted small"><?php echo htmlspecialchars($h['usuario_nome']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($historico)): ?>
                            <tr><td colspan="5" class="text-center p-4 text-muted">Nenhum ajuste registrado.</td></tr>
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