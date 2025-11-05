<?php
/*
 * ARQUIVO DE EXCLUSÃO DE CATEGORIA (CORRIGIDO)
 *
 * A LÓGICA PHP (verificar confirmação, excluir, redirecionar)
 * VEM ANTES de carregar o HTML (header.php).
 */

// 1. Inicia a sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. Segurança: Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit(); 
}

// 3. Inclui a lógica
require_once '../../src/categories.php';

// 4. Pega o ID da URL
$id_categoria = (int)$_GET['id'];
if ($id_categoria == 0) {
    header("Location: index.php");
    exit();
}

// 5. Verifica se o usuário confirmou (clicou no botão "Sim, Excluir")
$confirmado = isset($_GET['confirm']) && $_GET['confirm'] == '1';

if ($confirmado) {
    // ---- AÇÃO DE EXCLUIR ----
    
    // 6. Tenta excluir
    $sucesso = excluirCategoria($id_categoria);
    
    // 7. Redireciona de volta para a lista
    // Funciona agora, pois nenhum HTML foi enviado.
    header("Location: index.php");
    exit();
    
}

// -----------------------------------------------------------------
// SE CHEGOU AQUI, é para MOSTRAR A PÁGINA DE CONFIRMAÇÃO.
// -----------------------------------------------------------------

// 8. Busca os dados da categoria (só para mostrar o nome)
$categoria = buscarCategoriaPorId($id_categoria);
if (!$categoria) {
    header("Location: index.php"); // Categoria não existe
    exit();
}

// 9. Agora sim, podemos carregar o HTML
$page_title = 'Confirmar Exclusão';
$base_path = '../'; 
$pagina_ativa = 'cadastros'; 

include '../../templates/header.php'; // HTML começa AQUI
?>

<!-- CONTEÚDO DA PÁGINA (Confirmação) -->
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