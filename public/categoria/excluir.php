<?php
$page_title = 'Confirmar Exclusão';
$base_path = '../'; 
$pagina_ativa = 'cadastros'; 

include '../../templates/header.php';

// Segurança
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit(); 
}

// Lógica
require_once '../../src/categories.php';

$id_categoria = (int)$_GET['id'];
if ($id_categoria == 0) {
    header("Location: index.php");
    exit();
}

// Verifica se o usuário confirmou (clicou no botão "Sim, Excluir")
$confirmado = isset($_GET['confirm']) && $_GET['confirm'] == '1';

if ($confirmado) {
    // ---- AÇÃO DE EXCLUIR ----
    $sucesso = excluirCategoria($id_categoria);
    
    // NOTA: Se $sucesso for false (não conseguiu excluir), 
    // é provável que a categoria esteja em uso.
    // Em um sistema real, mostraríamos um erro. 
    // Aqui, apenas redirecionamos de volta.
    
    header("Location: index.php");
    exit();
    
} else {
    // --- MOSTRAR PÁGINA DE CONFIRMAÇÃO ---
    $categoria = buscarCategoriaPorId($id_categoria);
    if (!$categoria) {
        header("Location: index.php");
        exit();
    }
}
?>

<!-- Conteúdo da Página (Confirmação) -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            
            <div class="card shadow-lg border-2 border-danger">
                <div class="card-body p-4 p-md-5 text-center">
                    
                    <i class="fas fa-exclamation-triangle fa-4x text-danger mb-4"></i>

                    <h1 class="h3 fw-bold text-marrom-escuro mb-3">Confirmar Exclusão</h1>
                    
                    <p class="lead mb-4">
                        Você tem certeza que deseja excluir permanentemente a categoria:
                    </p>
                    
                    <h3 class="fw-bold text-danger bg-light p-3 rounded">
                        <?php echo htmlspecialchars($categoria['nome']); ?>
                    </h3>
                    
                    <p class="text-muted mt-4">
                        Atenção: Se esta categoria estiver sendo usada por algum produto,
                        a exclusão pode falhar.
                    </p>

                    <hr class="my-4">

                    <div class="d-flex justify-content-center gap-3">
                        <a href="index.php" class="btn btn-lg btn-secondary">
                            <i class="fas fa-times me-2"></i>
                            Cancelar
                        </a>
                        
                        <!-- Link de Confirmação (adiciona &confirm=1) -->
                        <a href="excluir.php?id=<?php echo $categoria['id']; ?>&confirm=1" 
                           class="btn btn-lg btn-danger">
                            <i class="fas fa-trash-alt me-2"></i>
                            Sim, Excluir
                        </a>
                    </div>

                </div>
            </div> 

        </div>
    </div> 
</div> 

<?php
include '../../templates/footer.php';
?>
