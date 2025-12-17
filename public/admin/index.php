<?php
// Configuração do caminho base para os includes funcionarem
$base_path = '../';
$page_title = 'Ferramentas Administrativas';
$pagina_ativa = 'admin';

include '../../templates/header.php';

// VERIFICAÇÃO DE SEGURANÇA
// Apenas admins podem ver esta página
if (!isset($_SESSION['user_cargo']) || $_SESSION['user_cargo'] !== 'admin') {
    // Redireciona para o dashboard comum com mensagem de erro (opcional)
    header("Location: ../dashboard.php");
    exit();
}

require_once '../../src/db.php';
require_once '../../src/dashboard_stats.php'; // Importa funções de estatísticas

// Lógica do Gerador de Hash
$hash_gerado = '';
$senha_digitada = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['gerar_hash'])) {
    $senha_digitada = $_POST['senha_para_hash'];
    if (!empty($senha_digitada)) {
        $hash_gerado = password_hash($senha_digitada, PASSWORD_DEFAULT);
    }
}

// Lógica das Estatísticas Gerais
function getCount($tabela) {
    $conn = getDBConnection();
    $sql = "SELECT COUNT(*) as total FROM $tabela";
    $res = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($res);
    mysqli_close($conn);
    return $data['total'];
}

$total_produtos = getCount('Produtos');
$total_fornecedores = getCount('Fornecedores');
$total_usuarios = getCount('Usuarios');

// Novas Estatísticas (Alertas)
$estoque_baixo = contarEstoqueBaixo();
$lotes_vencendo = contarLotesVencendo(30); // Próximos 30 dias
$movimentacoes_hoje = contarMovimentacoesHoje();

// Listas Recentes
$produtos_recentes = listarProdutosRecentes(5);
$usuarios_recentes = listarUsuariosRecentes(5);

?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-marrom-escuro"><i class="fas fa-tools me-2"></i>Ferramentas Administrativas</h1>
            <p class="text-muted">Área restrita para manutenção e verificações do sistema.</p>
        </div>
        <a href="../dashboard.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Voltar
        </a>
    </div>

    <!-- LINHA 1: Alertas do Sistema -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card shadow-sm border-0 h-100 border-start border-4 <?php echo ($estoque_baixo > 0) ? 'border-danger' : 'border-success'; ?>">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase mb-1">Estoque Baixo</h6>
                            <h2 class="mb-0 fw-bold <?php echo ($estoque_baixo > 0) ? 'text-danger' : 'text-success'; ?>">
                                <?php echo $estoque_baixo; ?>
                            </h2>
                        </div>
                        <div class="icon-shape bg-light rounded-circle p-3 text-primary">
                            <i class="fas fa-battery-quarter fa-2x <?php echo ($estoque_baixo > 0) ? 'text-danger' : 'text-success'; ?>"></i>
                        </div>
                    </div>
                    <small class="text-muted">SKUs abaixo do mínimo</small>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card shadow-sm border-0 h-100 border-start border-4 <?php echo ($lotes_vencendo > 0) ? 'border-warning' : 'border-success'; ?>">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase mb-1">Vencimento Próximo</h6>
                            <h2 class="mb-0 fw-bold <?php echo ($lotes_vencendo > 0) ? 'text-warning' : 'text-success'; ?>">
                                <?php echo $lotes_vencendo; ?>
                            </h2>
                        </div>
                        <div class="icon-shape bg-light rounded-circle p-3">
                            <i class="fas fa-hourglass-half fa-2x <?php echo ($lotes_vencendo > 0) ? 'text-warning' : 'text-success'; ?>"></i>
                        </div>
                    </div>
                    <small class="text-muted">Lotes vencendo em 30 dias</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 border-start border-4 border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase mb-1">Movimento Hoje</h6>
                            <div class="d-flex align-items-baseline">
                                <h4 class="mb-0 fw-bold text-success me-2">
                                    <i class="fas fa-arrow-down small"></i> <?php echo $movimentacoes_hoje['entradas'] ?? 0; ?>
                                </h4>
                                <h4 class="mb-0 fw-bold text-danger">
                                    <i class="fas fa-arrow-up small"></i> <?php echo $movimentacoes_hoje['saidas'] ?? 0; ?>
                                </h4>
                            </div>
                        </div>
                        <div class="icon-shape bg-light rounded-circle p-3">
                            <i class="fas fa-exchange-alt fa-2x text-info"></i>
                        </div>
                    </div>
                    <small class="text-muted">Entradas vs Saídas</small>
                </div>
            </div>
        </div>
    </div>

    <!-- LINHA 2: Ações Rápidas (REORGANIZADO) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h5 class="card-title fw-bold text-marrom-escuro">
                        <i class="fas fa-rocket me-2"></i>Ações Rápidas
                    </h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h6 class="text-muted text-uppercase small fw-bold mb-2">Cadastros</h6>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="../usuario/novo.php" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-user-plus me-1"></i>Usuário
                                </a>
                                <a href="../produto/novo.php" class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-box-open me-1"></i>Produto
                                </a>
                                <a href="../fornecedor/novo.php" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-truck me-1"></i>Fornecedor
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase small fw-bold mb-2">Operações</h6>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="../entrada/index.php" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-truck-loading me-1"></i>Nova Entrada
                                </a>
                                <a href="../saida/index.php" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-dolly me-1"></i>Nova Saída
                                </a>
                                <a href="logs.php" class="btn btn-sm btn-outline-dark">
                                    <i class="fas fa-history me-1"></i>Ver Logs
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- LINHA 3: Adicionados Recentemente (NOVO) -->
    <div class="row mb-4">
        <!-- Últimos Produtos -->
        <div class="col-md-6 mb-3 mb-md-0">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h6 class="card-title fw-bold text-marrom-escuro mb-0">
                        <i class="fas fa-box me-2"></i>Últimos Produtos
                    </h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <ul class="list-group list-group-flush">
                        <?php if (!empty($produtos_recentes)): ?>
                            <?php foreach ($produtos_recentes as $prod): ?>
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <span><?php echo htmlspecialchars($prod['nome']); ?></span>
                                    <span class="badge bg-light text-dark">ID <?php echo $prod['id']; ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item px-0 text-muted small">Nenhum produto recente.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Últimos Usuários -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h6 class="card-title fw-bold text-marrom-escuro mb-0">
                        <i class="fas fa-users me-2"></i>Últimos Usuários
                    </h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <ul class="list-group list-group-flush">
                        <?php if (!empty($usuarios_recentes)): ?>
                            <?php foreach ($usuarios_recentes as $usu): ?>
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="d-block"><?php echo htmlspecialchars($usu['nome']); ?></span>
                                        <small class="text-muted"><?php echo htmlspecialchars($usu['cargo']); ?></small>
                                    </div>
                                    <span class="badge bg-light text-dark">ID <?php echo $usu['id']; ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item px-0 text-muted small">Nenhum usuário recente.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- COLUNA 1: Gerador de Senhas -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h5 class="card-title fw-bold text-marrom-escuro">
                        <i class="fas fa-key me-2"></i>Gerador de Hash de Senha
                    </h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <p class="small text-muted">Use isso para criar senhas seguras manualmente para inserir no banco de dados.</p>
                    
                    <form method="POST" class="mb-3">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="senha_para_hash" 
                                   placeholder="Digite a senha (ex: 123456)" 
                                   value="<?php echo htmlspecialchars($senha_digitada); ?>" required>
                            <button class="btn btn-primary" type="submit" name="gerar_hash">
                                <i class="fas fa-bolt me-1"></i> Gerar
                            </button>
                        </div>
                    </form>

                    <?php if ($hash_gerado): ?>
                        <div class="alert alert-success">
                            <strong class="d-block mb-1">Hash Gerado:</strong>
                            <code class="user-select-all"><?php echo $hash_gerado; ?></code>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- COLUNA 2: Status do Sistema -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h5 class="card-title fw-bold text-marrom-escuro">
                        <i class="fas fa-server me-2"></i>Status do Sistema
                    </h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Versão do PHP</span>
                            <span class="badge bg-secondary rounded-pill"><?php echo phpversion(); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Servidor de Banco</span>
                            <span class="badge bg-info text-dark rounded-pill">MySQL (MariaDB)</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Data/Hora do Servidor</span>
                            <span class="text-muted small"><?php echo date('d/m/Y H:i:s'); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Timezone</span>
                            <span class="text-muted small"><?php echo date_default_timezone_get(); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Memória Limite</span>
                            <span class="text-muted small"><?php echo ini_get('memory_limit'); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Upload Máx.</span>
                            <span class="text-muted small"><?php echo ini_get('upload_max_filesize'); ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- LINHA 3: Resumo do Banco -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h5 class="card-title fw-bold text-marrom-escuro">
                        <i class="fas fa-database me-2"></i>Resumo do Banco de Dados
                    </h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="p-3 bg-light rounded-3">
                                <h2 class="fw-bold text-primary mb-0"><?php echo $total_produtos; ?></h2>
                                <span class="text-muted">Produtos Cadastrados</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="p-3 bg-light rounded-3">
                                <h2 class="fw-bold text-success mb-0"><?php echo $total_fornecedores; ?></h2>
                                <span class="text-muted">Fornecedores</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3">
                                <h2 class="fw-bold text-warning mb-0"><?php echo $total_usuarios; ?></h2>
                                <span class="text-muted">Usuários</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../templates/footer.php'; ?>
