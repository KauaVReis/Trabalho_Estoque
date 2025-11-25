<?php
$page_title = 'Editar Unidade';
$base_path = '../'; 
$pagina_ativa = 'cadastros'; 

include '../../templates/header.php';

if (!isset($_SESSION['user_id'])) { header("Location: ../index.php"); exit(); }

require_once '../../src/units.php';
$id = (int)$_GET['id'];
$unidade = buscarUnidadePorId($id);

if (!$unidade) { header("Location: index.php"); exit(); }
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <h1 class="display-6 text-marrom-escuro mb-4"><i class="fas fa-pencil-alt me-3"></i>Editar Unidade</h1>
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="processar.php">
                        <input type="hidden" name="id" value="<?php echo $unidade['id']; ?>">
                        <div class="mb-3">
                            <label for="nome" class="form-label fw-medium">Nome:</label>
                            <input type="text" class="form-control form-control-lg" id="nome" name="nome" value="<?php echo htmlspecialchars($unidade['nome']); ?>" required>
                        </div>
                        <div class="mb-4">
                            <label for="sigla" class="form-label fw-medium">Sigla:</label>
                            <input type="text" class="form-control form-control-lg" id="sigla" name="sigla" value="<?php echo htmlspecialchars($unidade['sigla']); ?>" required>
                        </div>
                        <hr class="my-4">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php" class="btn btn-lg btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-lg fw-medium" style="background-color: #38A169; color: white;">Salvar Alterações</button>
                        </div>
                    </form>
                </div> 
            </div> 
        </div> 
    </div> 
</div> 

<?php include '../../templates/footer.php'; ?>