<?php
$page_title = 'Registrar Perda/Ajuste';
$base_path = '../'; 
$pagina_ativa = 'ajuste';

include '../../templates/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit(); 
}

require_once '../../src/ajuste.php';
$lotes = listarLotesDisponiveis();
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <h1 class="display-6 text-marrom-escuro mb-4">
                <i class="fas fa-trash-alt me-3"></i>Registrar Perda / Descarte
            </h1>

            <div class="card shadow-sm border-0 rounded-3 border-start border-4 border-danger">
                <div class="card-body p-4 p-md-5">
                    
                    <form method="POST" action="processar.php">
                        
                        <!-- Seleção de Lote Específico -->
                        <div class="mb-4">
                            <label for="id_lote" class="form-label fw-medium">Selecione o Lote:</label>
                            <select class="form-select form-select-lg" id="id_lote" name="id_lote" required>
                                <option value="" disabled selected>Produto - Validade - Saldo...</option>
                                
                                <?php foreach ($lotes as $lote): ?>
                                    <?php 
                                        // Formata a data
                                        $data_val = $lote['data_validade'] ? date('d/m/Y', strtotime($lote['data_validade'])) : 'S/ Validade';
                                        
                                        // Marca visualmente se está vencido
                                        $vencido = ($lote['data_validade'] && strtotime($lote['data_validade']) < time()) ? '[VENCIDO]' : '';
                                        $style = $vencido ? 'color: red; font-weight: bold;' : '';
                                    ?>
                                    <option value="<?php echo $lote['id']; ?>" style="<?php echo $style; ?>">
                                        <?php echo $vencido; ?> 
                                        <?php echo htmlspecialchars($lote['produto_nome']); ?> 
                                        (<?php echo htmlspecialchars($lote['codigo_sku']); ?>) 
                                        - Val: <?php echo $data_val; ?> 
                                        - Qtd: <?php echo number_format($lote['quantidade_atual'], 2); ?>
                                    </option>
                                <?php endforeach; ?>
                                
                            </select>
                            <div class="form-text">Escolha o lote específico que será descartado ou ajustado.</div>
                        </div>

                        <!-- Quantidade -->
                        <div class="mb-3">
                            <label for="quantidade" class="form-label fw-medium">Quantidade a Descartar:</label>
                            <input type="number" class="form-control form-control-lg" id="quantidade" name="quantidade" 
                                   step="0.01" min="0.01" required placeholder="Ex: 10">
                        </div>
                        
                        <!-- Motivo (Observação) -->
                        <div class="mb-4">
                            <label for="observacao" class="form-label fw-medium">Motivo / Observação:</label>
                            <textarea class="form-control" id="observacao" name="observacao" rows="2" required 
                                      placeholder="Ex: Produto vencido; Embalagem rasgada; Furto..."></textarea>
                        </div>
                        
                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php" class="btn btn-lg btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-lg btn-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>Confirmar Perda
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