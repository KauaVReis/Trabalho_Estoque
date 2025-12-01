<?php
$page_title = 'Gerenciar SKUs';
$base_path = '../'; 
$pagina_ativa = 'cadastros'; 

include '../../templates/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); 
    exit(); 
}

require_once '../../src/skus.php';
$lista_skus = listarSkus();
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6 text-marrom-escuro">
            <i class="fas fa-barcode me-3"></i>Itens de Estoque (SKUs)
        </h1>
        <a href="novo.php" class="btn btn-lg fw-medium" style="background-color: #DD6B20; color: white;">
            <i class="fas fa-plus me-2"></i>Novo SKU
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Cód. SKU</th>
                            <th>Produto Base</th>
                            <th>Unidade</th>
                            <th class="text-center">Estoque Mín.</th>
                            <th class="text-center">Peso (kg)</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista_skus as $sku): ?>
                        <tr>
                            <td class="fw-bold"><?php echo htmlspecialchars($sku['codigo_sku']); ?></td>
                            <td>
                                <?php echo htmlspecialchars($sku['produto_nome']); ?>
                                <small class="text-muted d-block"><?php echo htmlspecialchars($sku['categoria_nome']); ?></small>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($sku['unidade_sigla']); ?></span></td>
                            <td class="text-center"><?php echo number_format($sku['estoque_minimo'], 2, ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($sku['peso_bruto_kg'], 3, ',', '.'); ?></td>
                            <td class="text-center">
                                <a href="editar.php?id=<?php echo $sku['id']; ?>" class="btn btn-sm btn-warning" title="Editar"><i class="fas fa-pencil-alt"></i></a>
                                <a href="excluir.php?id=<?php echo $sku['id']; ?>" class="btn btn-sm btn-danger" title="Excluir"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($lista_skus)): ?>
                            <tr><td colspan="6" class="text-center p-4 text-muted">Nenhum SKU cadastrado.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> 
</div> 

<?php include '../../templates/footer.php'; ?>