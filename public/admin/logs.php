<?php
// Configuração do caminho base
$base_path = '../';
$page_title = 'Logs do Sistema';
$pagina_ativa = 'admin';

include '../../templates/header.php';

// VERIFICAÇÃO DE SEGURANÇA
if (!isset($_SESSION['user_cargo']) || $_SESSION['user_cargo'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit();
}

require_once '../../src/logger.php';

// Busca logs
$logs = listarLogs(100); // Últimos 100 logs
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-marrom-escuro"><i class="fas fa-history me-2"></i>Logs do Sistema</h1>
            <p class="text-muted">Histórico de ações e eventos importantes.</p>
        </div>
        <a href="index.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Voltar
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Data/Hora</th>
                            <th>Usuário</th>
                            <th>Ação</th>
                            <th>Detalhes</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($logs)): ?>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td class="ps-4 text-nowrap text-muted small">
                                        <?php echo date('d/m/Y H:i:s', strtotime($log['data_hora'])); ?>
                                    </td>
                                    <td>
                                        <?php if ($log['usuario_nome']): ?>
                                            <span class="fw-bold text-dark"><?php echo htmlspecialchars($log['usuario_nome']); ?></span>
                                            <br><small class="text-muted"><?php echo htmlspecialchars($log['usuario_login']); ?></small>
                                        <?php else: ?>
                                            <span class="text-muted fst-italic">Sistema / Desconhecido</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $badgeClass = 'bg-secondary';
                                            if (stripos($log['acao'], 'Login') !== false) $badgeClass = 'bg-info text-dark';
                                            if (stripos($log['acao'], 'Criado') !== false) $badgeClass = 'bg-success';
                                            if (stripos($log['acao'], 'Excluído') !== false) $badgeClass = 'bg-danger';
                                            if (stripos($log['acao'], 'Atualizado') !== false) $badgeClass = 'bg-warning text-dark';
                                        ?>
                                        <span class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($log['acao']); ?></span>
                                    </td>
                                    <td>
                                        <?php if ($log['detalhes']): ?>
                                            <code class="small text-muted"><?php echo htmlspecialchars($log['detalhes']); ?></code>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-muted small"><?php echo htmlspecialchars($log['ip']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Nenhum registro encontrado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../../templates/footer.php'; ?>
