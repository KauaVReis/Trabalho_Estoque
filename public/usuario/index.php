<?php
// 1. INICIA A SESSÃO E VERIFICA O LOGIN E O CARGO
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. BLOQUEIO DE SEGURANÇA (SÓ ADMIN)
// Se não estiver logado ou não for 'admin', expulsa para o painel
if (!isset($_SESSION['user_id']) || $_SESSION['user_cargo'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit();
}

// 3. Includes e Variáveis de Página
require_once '../../src/users.php';
$usuarios = listarUsuarios();

$page_title = 'Gerenciar Usuários';
$base_path = '../'; 
$pagina_ativa = 'cadastros'; 

include '../../templates/header.php'; 
?>

<!-- CONTEÚDO DA PÁGINA -->
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-marrom-escuro">
            <i class="fas fa-users-cog me-2"></i>
            Gerenciar Usuários
        </h1>
        <a href="novo.php" class="btn btn-login">
            <i class="fas fa-plus me-2"></i>
            Novo Usuário
        </a>
    </div>

    <!-- Tabela de Usuários -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Login</th>
                            <th scope="col">Cargo</th>
                            <th scope="col" class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <th scope="row"><?php echo $usuario['id']; ?></th>
                            <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['login']); ?></td>
                            <td>
                                <!-- Destaca o admin -->
                                <?php if ($usuario['cargo'] == 'admin'): ?>
                                    <span class="badge bg-danger">
                                        <?php echo htmlspecialchars($usuario['cargo']); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">
                                        <?php echo htmlspecialchars($usuario['cargo']); ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <a href="editar.php?id=<?php echo $usuario['id']; ?>" 
                                   class="btn btn-sm btn-outline-primary" 
                                   title="Editar">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                
                                <?php // Impede que o admin se auto-exclua ?>
                                <?php if ($usuario['id'] != $_SESSION['user_id']): ?>
                                    <a href="excluir.php?id=<?php echo $usuario['id']; ?>" 
                                       class="btn btn-sm btn-outline-danger" 
                                       title="Excluir">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                <?php else: ?>
                                     <span class="btn btn-sm btn-outline-secondary disabled" 
                                           title="Você não pode se auto-excluir">
                                        <i class="fas fa-trash-alt"></i>
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
include '../../templates/footer.php';
?>