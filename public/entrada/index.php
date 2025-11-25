<?php
$page_title = 'Histórico de Entradas';
$base_path = '../'; 
$pagina_ativa = 'entrada'; 

include '../../templates/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); 
    exit(); 
}

require_once '../../src/lotes.php';
$entradas = listarUltimasEntradas();
?>

<div class="container my-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6 text-marrom-escuro">
            <i class="fas fa-history me-3"></i>Histórico de Entradas
        </h1>
        <a href="nova.php" class="btn btn-lg fw-medium" style="background-color: #DD6B20; color: white;">
            <i class="fas fa-plus me-2"></i>Nova Entrada
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
                            <th>Fornecedor</th>
                            <th>Qtd</th>
                            <th>Validade</th>
                            <th>Responsável</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($entradas as $e): ?>
                        <tr>
                            <td><?php echo date('d/m/Y H:i', strtotime($e['data_movimento'])); ?></td>
                            <td class="fw-bold"><?php echo htmlspecialchars($e['produto_nome']); ?></td>
                            <td class="text-muted small"><?php echo htmlspecialchars($e['fornecedor_nome']); ?></td>
                            <td class="text-success fw-bold">+<?php echo $e['quantidade']; ?></td>
                            <td>
                                <?php 
                                    $data_val = strtotime($e['data_validade']);
                                    $hoje = time();
                                    $classe_val = ($data_val < $hoje) ? 'text-danger fw-bold' : '';
                                    echo "<span class='$classe_val'>" . date('d/m/Y', $data_val) . "</span>";
                                ?>
                            </td>
                            <td class="text-muted small"><?php echo htmlspecialchars($e['usuario_nome']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($entradas)): ?>
                            <tr><td colspan="6" class="text-center p-4 text-muted">Nenhuma entrada registrada.</td></tr>
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