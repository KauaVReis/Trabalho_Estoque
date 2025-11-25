<?php
$page_title = 'Registrar Saída';
$base_path = '../'; 
$pagina_ativa = 'saida'; // Você pode adicionar essa opção no menu

include '../../templates/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit(); 
}

// Usamos a lógica de SKUs para preencher o select
require_once '../../src/skus.php'; 
$lista_skus = listarSkusParaSelect();
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <h1 class="display-6 text-marrom-escuro mb-4">
                <i class="fas fa-dolly me-3"></i>Registrar Saída (Venda)
            </h1>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4 p-md-5">
                    
                    <form method="POST" action="processar.php">
                        
                        <!-- Seleção de SKU -->
                        <div class="mb-4">
                            <label for="id_item_sku" class="form-label fw-medium">Item (SKU):</label>
                            <select class="form-select form-select-lg" id="id_item_sku" name="id_item_sku" required>
                                <option value="" disabled selected>Selecione o item...</option>
                                <?php if (empty($lista_skus)): ?>
                                    <option value="" disabled>Nenhum item cadastrado com SKU.</option>
                                <?php else: ?>
                                    <?php foreach ($lista_skus as $sku): ?>
                                        <option value="<?php echo $sku['id']; ?>">
                                            <?php echo htmlspecialchars($sku['produto_nome']); ?> 
                                            [<?php echo htmlspecialchars($sku['codigo_sku']); ?>]
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Quantidade -->
                        <div class="mb-4">
                            <label for="quantidade" class="form-label fw-medium">Quantidade a Retirar:</label>
                            <input type="number" class="form-control form-control-lg" id="quantidade" name="quantidade" 
                                   step="0.01" min="0.01" required placeholder="Ex: 5">
                            <div class="form-text text-muted">
                                O sistema baixará automaticamente dos lotes com validade mais próxima (Lógica FEFO).
                            </div>
                        </div>
                        
                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="../dashboard.php" class="btn btn-lg btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-lg fw-medium" style="background-color: #38A169; color: white;">
                                <i class="fas fa-check-circle me-2"></i>Confirmar Saída
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