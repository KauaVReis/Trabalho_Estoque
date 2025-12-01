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

<!-- Carrega biblioteca de PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<div class="container my-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6 text-marrom-escuro">
            <i class="fas fa-boxes me-3"></i>Estoque Atual
        </h1>
        <div>
            <!-- Botão de PDF -->
            <button onclick="gerarPDF()" class="btn btn-secondary me-2">
                <i class="fas fa-file-pdf me-2"></i>Relatório
            </button>
            
            <a href="../entrada/nova.php" class="btn btn-success me-2">
                <i class="fas fa-plus-circle me-2"></i>Entrada
            </a>
            <a href="../saida/nova.php" class="btn btn-danger">
                <i class="fas fa-minus-circle me-2"></i>Saída
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-3" id="tabelaParaImprimir">
        <div class="card-body">
            
            <!-- Cabeçalho apenas para o PDF (invisível na tela) -->
            <div class="d-none d-print-block mb-4 text-center" id="pdfHeader">
                <h3>Relatório de Estoque</h3>
                <p>Data: <?php echo date('d/m/Y H:i'); ?></p>
                <hr>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>SKU / Produto</th>
                            <th>Saldo Disponível</th>
                            <th>Status / Vencidos</th>
                            <th class="text-end d-print-none">Ações</th> <!-- Esconde no PDF -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista_estoque as $item): ?>
                        <tr>
                            <!-- Coluna Produto -->
                            <td>
                                <div class="fw-bold"><?php echo htmlspecialchars($item['produto_nome']); ?></div>
                                <div class="text-muted small"><?php echo htmlspecialchars($item['codigo_sku']); ?></div>
                            </td>

                            <!-- Coluna Saldo -->
                            <td>
                                <span class="fw-bold fs-5 text-success">
                                    <?php echo number_format($item['saldo_valido'], 2, ',', '.'); ?>
                                </span> 
                                <small class="text-muted"><?php echo htmlspecialchars($item['unidade']); ?></small>
                            </td>
                            
                            <!-- Coluna Status -->
                            <td>
                                <?php 
                                    if ($item['saldo_valido'] <= 0) {
                                        echo '<span class="badge bg-secondary">Sem Estoque</span>';
                                    } elseif ($item['saldo_valido'] <= $item['estoque_minimo']) {
                                        echo '<span class="badge bg-danger">Baixo</span>';
                                    } else {
                                        echo '<span class="badge bg-success">OK</span>';
                                    }
                                ?>
                                <?php if ($item['saldo_vencido'] > 0): ?>
                                    <div class="text-danger small fw-bold mt-1">
                                        VENCIDOS: <?php echo number_format($item['saldo_vencido'], 2, ',', '.'); ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            
                            <td class="text-end d-print-none"> <!-- Esconde no PDF -->
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
                    </tbody>
                </table>
            </div>

        </div>
    </div> 
</div> 

<script>
    function gerarPDF() {
        const elemento = document.getElementById('tabelaParaImprimir');
        
        // Mostra o cabeçalho do PDF temporariamente
        const header = document.getElementById('pdfHeader');
        header.classList.remove('d-none');
        
        const opt = {
            margin:       10,
            filename:     'relatorio_estoque.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        // Gera o PDF
        html2pdf().set(opt).from(elemento).save().then(() => {
            // Esconde o cabeçalho de volta
            header.classList.add('d-none');
        });
    }
</script>

<?php
include '../../templates/footer.php';
?>