<?php
$page_title = 'Novo SKU';
$base_path = '../'; 
$pagina_ativa = 'cadastros'; 

include '../../templates/header.php';

if (!isset($_SESSION['user_id'])) { header("Location: ../index.php"); exit(); }

require_once '../../src/products.php';
require_once '../../src/units.php';

// Busca dados para os selects
$produtos = listarProdutos();
$unidades = listarUnidades();
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="display-6 text-marrom-escuro mb-4"><i class="fas fa-plus-circle me-3"></i>Novo SKU</h1>
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="processar.php">
                        
                        <!-- Produto Pai -->
                        <div class="mb-3">
                            <label for="id_produto" class="form-label fw-medium">Produto Base:</label>
                            <select class="form-select form-select-lg" id="id_produto" name="id_produto" required>
                                <option value="" disabled selected>Selecione...</option>
                                <?php foreach ($produtos as $p): ?>
                                    <option value="<?php echo $p['id']; ?>">
                                        <?php echo htmlspecialchars($p['nome']); ?> (<?php echo htmlspecialchars($p['categoria_nome']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Código e Unidade -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="codigo_sku" class="form-label fw-medium">Código SKU (Único):</label>
                                <input type="text" class="form-control form-control-lg" id="codigo_sku" name="codigo_sku" placeholder="Ex: TIN-BR-18L" required>
                            </div>
                            <div class="col-md-6">
                                <label for="id_unidade" class="form-label fw-medium">Unidade de Medida:</label>
                                <select class="form-select form-select-lg" id="id_unidade" name="id_unidade" required>
                                    <option value="" disabled selected>Selecione...</option>
                                    <?php foreach ($unidades as $u): ?>
                                        <option value="<?php echo $u['id']; ?>"><?php echo htmlspecialchars($u['nome']); ?> (<?php echo htmlspecialchars($u['sigla']); ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Detalhes Técnicos -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="estoque_minimo" class="form-label fw-medium">Estoque Mínimo (Alerta):</label>
                                <input type="number" step="0.001" class="form-control" id="estoque_minimo" name="estoque_minimo" placeholder="0.000">
                            </div>
                            <div class="col-md-6">
                                <label for="peso_bruto_kg" class="form-label fw-medium">Peso Bruto (kg):</label>
                                <input type="number" step="0.001" class="form-control" id="peso_bruto_kg" name="peso_bruto_kg" placeholder="0.000">
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php" class="btn btn-lg btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-lg fw-medium" style="background-color: #38A169; color: white;">Salvar SKU</button>
                        </div>
                    </form>
                </div> 
            </div> 
        </div> 
    </div> 
</div> 

<?php include '../../templates/footer.php'; ?>