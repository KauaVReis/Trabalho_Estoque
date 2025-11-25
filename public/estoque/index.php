<?php
$page_title = 'Controle de Estoque';
$base_path = '../'; 
$pagina_ativa = 'estoque'; 

include '../../templates/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); 
    exit(); 
}

require_once '../../src/estoque.php';
$lista_estoque = listarEstoqueGeral();
?>

<div class="container my-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6 text-marrom-escuro">
            <i class="fas fa-boxes me-3"></i>Estoque Atual
        </h1>
        <div>
            <a href="../entrada/nova.php" class="btn btn-success me-2">
                <i class="fas fa-plus-circle me-2"></i>Entrada
            </a>
            <a href="../saida/nova.php" class="btn btn-danger">
                <i class="fas fa-minus-circle me-2"></i>Saída
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>SKU / Produto</th>
                            <th>Fornecedores (Atuais)</th>
                            <th class="text-center">Saldo</th>
                            <th class="text-center">Próx. Validade</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista_estoque as $item): ?>
                        <tr>
                            <!-- Coluna Produto/SKU -->
                            <td>
                                <div class="fw-bold"><?php echo htmlspecialchars($item['produto_nome']); ?></div>
                                <div class="text-muted small"><?php echo htmlspecialchars($item['codigo_sku']); ?></div>
                            </td>
                            
                            <!-- Coluna Fornecedores -->
                            <td>
                                <small class="text-secondary">
                                    <?php echo !empty($item['fornecedores']) ? htmlspecialchars($item['fornecedores']) : '-'; ?>
                                </small>
                            </td>

                            <!-- Coluna Saldo -->
                            <td class="text-center">
                                <span class="fw-bold fs-5"><?php echo number_format($item['saldo_total'], 2, ',', '.'); ?></span> 
                                <small class="text-muted"><?php echo htmlspecialchars($item['unidade']); ?></small>
                            </td>
                            
                            <!-- Coluna Validade -->
                            <td class="text-center">
                                <?php 
                                    if ($item['proxima_validade']) {
                                        $data_val = strtotime($item['proxima_validade']);
                                        $hoje = time();
                                        $dias_para_vencer = ($data_val - $hoje) / (60 * 60 * 24);
                                        
                                        $badge_class = 'bg-success';
                                        $texto_aviso = '';

                                        if ($dias_para_vencer < 0) {
                                            $badge_class = 'bg-dark'; // Vencido
                                            $texto_aviso = '(Vencido)';
                                        } elseif ($dias_para_vencer < 30) {
                                            $badge_class = 'bg-danger'; // Crítico
                                        } elseif ($dias_para_vencer < 90) {
                                            $badge_class = 'bg-warning text-dark'; // Atenção
                                        }

                                        echo "<span class='badge $badge_class'>" . date('d/m/Y', $data_val) . " $texto_aviso</span>";
                                    } else {
                                        echo '<span class="text-muted small">-</span>';
                                    }
                                ?>
                            </td>

                            <!-- Coluna Status Estoque -->
                            <td class="text-center">
                                <?php 
                                    if ($item['saldo_total'] <= 0) {
                                        echo '<span class="badge bg-secondary">Zerado</span>';
                                    } elseif ($item['saldo_total'] <= $item['estoque_minimo']) {
                                        echo '<span class="badge bg-danger">Baixo</span>';
                                    } else {
                                        echo '<span class="badge bg-success">OK</span>';
                                    }
                                ?>
                            </td>
                            
                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    <a href="../entrada/nova.php?sku=<?php echo $item['id_sku']; ?>" 
                                       class="btn btn-sm btn-outline-success" title="Dar Entrada">
                                        <i class="fas fa-arrow-down"></i>
                                    </a>
                                    <a href="../saida/nova.php?sku=<?php echo $item['id_sku']; ?>" 
                                       class="btn btn-sm btn-outline-danger" title="Dar Saída">
                                        <i class="fas fa-arrow-up"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($lista_estoque)): ?>
                            <tr><td colspan="6" class="text-center p-4 text-muted">Nenhum item com SKU cadastrado ou movimentado.</td></tr>
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