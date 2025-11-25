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
                            <th class="text-center">Saldo Disponível</th>
                            <th class="text-center">Próx. Validade</th>
                            <th class="text-center">Status / Vencidos</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista_estoque as $item): ?>
                        <tr>
                            <!-- Coluna Produto -->
                            <td>
                                <div class="fw-bold"><?php echo htmlspecialchars($item['produto_nome']); ?></div>
                                <div class="text-muted small"><?php echo htmlspecialchars($item['codigo_sku']); ?></div>
                                <div class="text-muted smaller" style="font-size: 0.8em;">
                                    Forn: <?php echo !empty($item['fornecedores']) ? htmlspecialchars($item['fornecedores']) : '-'; ?>
                                </div>
                            </td>

                            <!-- Coluna Saldo (Apenas Válidos) -->
                            <td class="text-center">
                                <span class="fw-bold fs-5 text-success">
                                    <?php echo number_format($item['saldo_valido'], 2, ',', '.'); ?>
                                </span> 
                                <small class="text-muted"><?php echo htmlspecialchars($item['unidade']); ?></small>
                            </td>
                            
                            <!-- Coluna Próxima Validade (Dos Válidos) -->
                            <td class="text-center">
                                <?php 
                                    if ($item['proxima_validade']) {
                                        $data_val = strtotime($item['proxima_validade']);
                                        $hoje = time();
                                        $dias_para_vencer = ($data_val - $hoje) / (60 * 60 * 24);
                                        
                                        $badge_class = 'bg-success';
                                        
                                        if ($dias_para_vencer < 30) {
                                            $badge_class = 'bg-warning text-dark'; // Atenção (perto de vencer)
                                        }

                                        echo "<span class='badge $badge_class'>" . date('d/m/Y', $data_val) . "</span>";
                                        if ($dias_para_vencer < 30) {
                                            echo "<div class='text-danger small fw-bold mt-1'>Atenção!</div>";
                                        }
                                    } else {
                                        echo '<span class="text-muted small">-</span>';
                                    }
                                ?>
                            </td>

                            <!-- Coluna Status e Vencidos -->
                            <td class="text-center">
                                <!-- Status do Estoque Válido -->
                                <?php 
                                    if ($item['saldo_valido'] <= 0) {
                                        echo '<span class="badge bg-secondary mb-1">Sem Estoque</span><br>';
                                    } elseif ($item['saldo_valido'] <= $item['estoque_minimo']) {
                                        echo '<span class="badge bg-danger mb-1">Estoque Baixo</span><br>';
                                    } else {
                                        echo '<span class="badge bg-light text-dark border mb-1">OK</span><br>';
                                    }
                                ?>

                                <!-- ALERTA DE VENCIDOS (Se houver) -->
                                <?php if ($item['saldo_vencido'] > 0): ?>
                                    <div class="mt-2 p-1 bg-danger bg-opacity-10 border border-danger rounded">
                                        <small class="text-danger fw-bold d-block">
                                            <i class="fas fa-exclamation-circle"></i> VENCIDOS
                                        </small>
                                        <small class="text-dark">
                                            Qtd: <strong><?php echo number_format($item['saldo_vencido'], 2, ',', '.'); ?></strong>
                                        </small>
                                        <?php if($item['data_vencido_recente']): ?>
                                            <br><small class="text-muted" style="font-size: 0.75em;">
                                                Data: <?php echo date('d/m/Y', strtotime($item['data_vencido_recente'])); ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
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
                            <tr><td colspan="5" class="text-center p-4 text-muted">Nenhum item com SKU cadastrado.</td></tr>
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