<?php
$page_title = 'Nova Entrada de Estoque';
$base_path = '../'; 
$pagina_ativa = 'entrada'; 

include '../../templates/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit(); 
}

// ATUALIZADO: Agora incluímos a lógica de SKUs
require_once '../../src/skus.php'; 
require_once '../../src/fornecedores.php';

// ATUALIZADO: Buscamos SKUs em vez de produtos
$lista_skus = listarSkusParaSelect();
$fornecedores = listarFornecedores();
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <h1 class="display-6 text-marrom-escuro mb-4">
                <i class="fas fa-truck-loading me-3"></i>Registrar Entrada
            </h1>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4 p-md-5">
                    
                    <form method="POST" action="processar.php">
                        
                        <!-- Seleção de SKU (ATUALIZADO) -->
                        <div class="mb-4">
                            <label for="id_item_sku" class="form-label fw-medium">Item (SKU):</label>
                            <select class="form-select form-select-lg" id="id_item_sku" name="id_item_sku" required>
                                <option value="" disabled selected>Selecione o item específico...</option>
                                
                                <?php if (empty($lista_skus)): ?>
                                    <option value="" disabled>Nenhum SKU cadastrado. Cadastre variações primeiro.</option>
                                <?php else: ?>
                                    <?php foreach ($lista_skus as $sku): ?>
                                        <option value="<?php echo $sku['id']; ?>">
                                            <?php echo htmlspecialchars($sku['produto_nome']); ?> 
                                            [<?php echo htmlspecialchars($sku['codigo_sku']); ?>]
                                            <?php echo $sku['unidade_sigla'] ? ' - ' . $sku['unidade_sigla'] : ''; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                
                            </select>
                            <div class="form-text">Selecione a variação exata (ex: Cor, Tamanho) que está entrando.</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="quantidade" class="form-label fw-medium">Quantidade:</label>
                                <input type="number" class="form-control" id="quantidade" name="quantidade" 
                                       step="0.01" min="0.01" required placeholder="Ex: 50">
                            </div>
                            <div class="col-md-6">
                                <label for="validade" class="form-label fw-medium text-danger">Data de Validade:</label>
                                <input type="date" class="form-control" id="validade" name="validade" required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="id_fornecedor" class="form-label fw-medium">Fornecedor:</label>
                                <select class="form-select" id="id_fornecedor" name="id_fornecedor" required>
                                    <option value="" disabled selected>Quem forneceu?</option>
                                    <?php foreach ($fornecedores as $f): ?>
                                        <option value="<?php echo $f['id']; ?>">
                                            <?php echo htmlspecialchars($f['razao_social']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="preco_custo" class="form-label fw-medium">Custo Unitário (R$):</label>
                                <input type="number" class="form-control" id="preco_custo" name="preco_custo" 
                                       step="0.01" min="0" placeholder="0.00">
                            </div>
                        </div>
                        
                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="../dashboard.php" class="btn btn-lg btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-lg fw-medium" style="background-color: #DD6B20; color: white;">
                                <i class="fas fa-save me-2"></i>Confirmar Entrada
                            </button>
                        </div>

                    </form>

                </div> 
            </div> 

        </div> 
    </div> 
</div> 

<?php
include '../../templates/footer.php';
?>