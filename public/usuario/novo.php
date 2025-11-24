<?php
// 1. SESSÃO E SEGURANÇA (SÓ ADMIN)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['user_cargo'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit();
}

// 2. Includes e Variáveis de Página
$page_title = 'Novo Usuário';
$base_path = '../'; 
$pagina_ativa = 'cadastros'; 

include '../../templates/header.php'; 
?>

<!-- CONTEÚDO DA PÁGINA -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="h3 fw-bold text-marrom-escuro mb-4">
                <i class="fas fa-user-plus me-2"></i>
                Novo Usuário
            </h1>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    
                    <!-- Formulário aponta para o processador -->
                    <form action="processar.php" method="POST">
                        <!-- Ação "cadastrar" -->
                        <input type="hidden" name="acao" value="cadastrar">

                        <!-- Linha 1: Nome -->
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>

                        <!-- Linha 2: Login e Cargo -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="login" class="form-label">Login</label>
                                <input type="text" class="form-control" id="login" name="login" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cargo" class="form-label">Cargo</label>
                                <select class="form-select" id="cargo" name="cargo" required>
                                    <option value="" disabled selected>Selecione...</option>
                                    <option value="admin">Admin (Total)</option>
                                    <option value="estoquista">Estoquista (Entrada/Saída)</option>
                                    <option value="vendedor">Vendedor (Saída)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Linha 3: Senha -->
                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="senha" name="senha" required>
                            <div class="form-text">
                                A senha deve ser forte (será criptografada).
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Botões -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-login">
                                <i class="fas fa-save me-2"></i>
                                Salvar
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