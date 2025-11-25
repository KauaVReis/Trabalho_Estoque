<?php
$page_title = 'Histórico de Saídas';
$base_path = '../'; 
$pagina_ativa = 'saida'; 

include '../../templates/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); 
    exit(); 
}

require_once '../../src/saida.php';
$saidas = listarUltimasSaidas();
?>

<div class="container my-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6 text-marrom-escuro">
            <i class="fas fa-history me-3"></i>Histórico de Saídas (Vendas)
        </h1>
        <a href="nova.php" class="btn btn-lg fw-medium" style="background-color: #38A169; color: white;">
            <i class="fas fa-plus me-2"></i>Nova Saída
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
                            <th>Qtd</th>
                            <th>Responsável</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($saidas as $s): ?>
                        <tr>
                            <td><?php echo date('d/m/Y H:i', strtotime($s['data_movimento'])); ?></td>
                            <td class="fw-bold">
                                <?php echo htmlspecialchars($s['produto_nome']); ?>
                                <?php if(!empty($s['codigo_sku'])): ?>
                                    <small class="text-muted ms-2">[<?php echo htmlspecialchars($s['codigo_sku']); ?>]</small>
                                <?php endif; ?>
                            </td>
                            <td class="text-danger fw-bold">-<?php echo $s['quantidade']; ?></td>
                            <td class="text-muted small"><?php echo htmlspecialchars($s['usuario_nome']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($saidas)): ?>
                            <tr><td colspan="4" class="text-center p-4 text-muted">Nenhuma saída registrada.</td></tr>
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