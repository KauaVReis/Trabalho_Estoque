<?php
/*
 * ARQUIVO DE EXCLUSÃO DE USUÁRIO (Lógica no topo)
 */

// 1. SESSÃO E SEGURANÇA (SÓ ADMIN)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['user_cargo'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit();
}

// 2. Inclui a lógica
require_once '../../src/users.php';

// 3. Pega o ID da URL
$id_usuario = (int)$_GET['id'];
if ($id_usuario == 0) {
    header("Location: index.php"); 
    exit();
}

// 4. BLOQUEIO DE SEGURANÇA (Não pode se auto-excluir)
if ($id_usuario == $_SESSION['user_id']) {
    header("Location: index.php");
    exit();
}

// 5. Verifica se o usuário confirmou
$confirmado = isset($_GET['confirm']) && $_GET['confirm'] == '1';

if ($confirmado) {
    // ---- AÇÃO DE EXCLUIR ----
    $sucesso = excluirUsuario($id_usuario);
    header("Location: index.php");
    exit();
}

// -----------------------------------------------------------------
// SE CHEGOU AQUI, é para MOSTRAR A PÁGINA DE CONFIRMAÇÃO.
// -----------------------------------------------------------------

// 6. Busca os dados do usuário (só para mostrar o nome)
$usuario = buscarUsuarioPorId($id_usuario);
if (!$usuario) {
    header("Location: index.php"); 
    exit();
}

// 7. Agora sim, podemos carregar o HTML
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
                        Você tem certeza que deseja excluir permanentemente o usuário:
                    </p>
                    
                    <h3 class="fw-bold text-danger bg-light p-3 rounded">
                        <?php echo htmlspecialchars($usuario['nome']); ?>
                        (Login: <?php echo htmlspecialchars($usuario['login']); ?>)
                    </h3>
                    
                    <p class="text-muted mt-4">
                        Esta ação não pode ser desfeita.
                    </p>

                    <hr class="my-4">

                    <div class="d-flex justify-content-center gap-3">
                        <a href="index.php" class="btn btn-lg btn-secondary">
                            <i class="fas fa-times me-2"></i>
                            Cancelar
                        </a>
                        
                        <!-- Link de Confirmação (adiciona &confirm=1) -->
                        <a href="excluir.php?id=<?php echo $usuario['id']; ?>&confirm=1" 
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