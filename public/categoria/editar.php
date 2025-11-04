<?php
$page_title = 'Editar Categoria';
$base_path = '../'; 
$pagina_ativa = 'cadastros'; 

include '../../templates/header.php';

// Segurança
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit(); 
}

// Lógica: Buscar a categoria que será editada
require_once '../../src/categories.php';

// 1. Pega o ID da URL (editar.php?id=...)
$id_categoria = (int)$_GET['id'];
if ($id_categoria == 0) {
    header("Location: index.php");
    exit();
}

// 2. Busca os dados no banco
$categoria = buscarCategoriaPorId($id_categoria);
if (!$categoria) {
    header("Location: index.php");
    exit();
}
?>

<!-- Conteúdo da Página -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            
            <h1 class="display-6 text-marrom-escuro mb-4">
                <i class="fas fa-pencil-alt me-3"></i>Editar Categoria
            </h1>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4 p-md-5">
                    
                    <form method="POST" action="processar.php">
                        
                        <!-- CAMPO OCULTO (HIDDEN) com o ID -->
                        <input type="hidden" name="id" value="<?php echo $categoria['id']; ?>">

                        <!-- Campo Nome (preenchido) -->
                        <div class="mb-4">
                            <label for="nome" class="form-label fw-medium">Nome da Categoria:</label>
                            <input type="text" class="form-control form-control-lg" 
                                   id="nome" name="nome"
                                   value="<?php echo htmlspecialchars($categoria['nome']); ?>"
                                   required>
                        </div>
                        
                        <hr class="my-4">

                        <!-- Botões -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php" class="btn btn-lg btn-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-lg fw-medium" style="background-color: #38A169; color: white;">
                                <i class="fas fa-save me-2"></i>Salvar Alterações
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
