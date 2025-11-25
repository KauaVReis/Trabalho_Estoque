<?php
// 1. Inicia a sessão no topo
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. Verifica se já está logado ANTES de carregar qualquer HTML
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit(); // Encerra o script imediatamente
}

// 3. Configurações da página
$page_title = 'Login - Gestão de Depósito';

// 4. Agora sim, carrega o cabeçalho HTML
include '../templates/header.php';

// Verifica mensagem de erro
$error_message = '';
if (isset($_SESSION['login_error'])) {
    $error_message = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8 col-sm-10" style="margin-top: 10vh;">
            
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body p-4 p-sm-5">
                    
                    <div class="text-center mb-4">  
                        <i class="fas fa-warehouse fa-3x text-marrom-escuro mb-3"></i>
                        <h1 class="h3 fw-bold mb-0 text-marrom-escuro">Acesso ao Sistema</h1>
                        <p class="text-muted">Gestão de Materiais de Construção</p>
                    </div>

                    <form method="POST" action="login_process.php">
                        
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="login" class="form-label fw-medium">Login:</label>
                            <input type="text" class="form-control form-control-lg" 
                                   id="login" name="login" 
                                   required placeholder="Seu usuário de login">
                        </div>
                        
                        <div class="mb-4">
                            <label for="senha" class="form-label fw-medium">Senha:</label>
                            <input type="password" class="form-control form-control-lg" 
                                   id="senha" name="senha" 
                                   required placeholder="Sua senha">
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-login btn-lg fw-bold">
                                <i class="fas fa-sign-in-alt me-2"></i>Entrar
                            </button>
                        </div>
                    </form>
                    
                </div>
            </div>

        </div>
    </div>
</div>

<?php
include '../templates/footer.php';
?>