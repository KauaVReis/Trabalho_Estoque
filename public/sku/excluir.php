<?php
// Lógica no topo
if (session_status() == PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id'])) { header("Location: ../index.php"); exit(); }

require_once '../../src/skus.php';
$id = (int)$_GET['id'];

if (isset($_GET['confirm']) && $_GET['confirm'] == '1') {
    if(excluirSku($id)) {
        header("Location: index.php");
    } else {
        // Erro provável: SKU em uso em Lotes
        echo "<script>alert('Erro: Não é possível excluir este SKU pois ele já possui movimentações ou lotes associados.'); window.location='index.php';</script>";
    }
    exit();
}

$sku = buscarSkuPorId($id);
if (!$sku) { header("Location: index.php"); exit(); }

$page_title = 'Confirmar Exclusão';
$base_path = '../'; 
$pagina_ativa = 'cadastros'; 
include '../../templates/header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-lg border-2 border-danger">
                <div class="card-body p-4 p-md-5 text-center">
                    <i class="fas fa-exclamation-triangle fa-4x text-danger mb-4"></i>
                    <h1 class="h3 fw-bold text-marrom-escuro mb-3">Confirmar Exclusão</h1>
                    <p class="lead mb-4">Deseja excluir o SKU: <strong><?php echo htmlspecialchars($sku['codigo_sku']); ?></strong>?</p>
                    <p class="text-muted small">Esta ação não pode ser desfeita e falhará se houver estoque para este item.</p>
                    <hr class="my-4">
                    <div class="d-flex justify-content-center gap-3">
                        <a href="index.php" class="btn btn-lg btn-secondary">Cancelar</a>
                        <a href="excluir.php?id=<?php echo $sku['id']; ?>&confirm=1" class="btn btn-lg btn-danger">Sim, Excluir</a>
                    </div>
                </div>
            </div> 
        </div>
    </div> 
</div> 

<?php include '../../templates/footer.php'; ?>