<?php
// Define o título da página (usado no header.php)
$page_title = 'Login - Gestão de Depósito';

// Inclui o cabeçalho
include '../templates/header.php';

// Se o usuário JÁ ESTIVER LOGADO, redireciona para o dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit(); // Para o script
}

// Verifica se há uma mensagem de erro (enviada pelo login_process.php)
$error_message = '';
if (isset($_SESSION['login_error'])) {
    $error_message = $_SESSION['login_error'];
    // Limpa o erro da sessão para não mostrar de novo
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

                    <!-- Formulário de Login -->
                    <form method="POST" action="login_process.php">
                        
                        <!-- Mostra o erro de login, se existir -->
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>

                        <!-- ATUALIZADO: De 'email' para 'login' -->
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
// Inclui o rodapé
include '../templates/footer.php';
?>

