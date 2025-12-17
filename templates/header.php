<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('America/Sao_Paulo');

// Variáveis de estado
$usuario_logado = isset($_SESSION['user_id']);
$is_admin = (isset($_SESSION['user_cargo']) && $_SESSION['user_cargo'] == 'admin');

// Configurações de página (defaults)
if (!isset($base_path)) {
    $base_path = '';
}
if (!isset($pagina_ativa)) {
    $pagina_ativa = '';
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Gestão de Depósito'; ?></title>
    <link rel="icon" type="image/svg+xml" href="<?php echo $base_path; ?>assets/img/favicon.svg">

    <!-- CSS Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #FAFAFA;
        }

        .navbar-custom {
            background-color: #51423D;
        }

        .text-marrom-escuro {
            color: #51423D;
        }

        .btn-login {
            background-color: #51423D;
            border-color: #51423D;
            color: white;
        }

        .btn-login:hover {
            background-color: #3a2f2c;
            border-color: #3a2f2c;
        }

        .navbar-nav .nav-link.active,
        .navbar-nav .nav-item.active .nav-link {
            font-weight: 600;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100"> <!-- Garante que o footer fique embaixo -->

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm">
        <div class="container">

            <a class="navbar-brand fw-bold" href="<?php echo $base_path . 'dashboard.php'; ?>">
                <i class="fas fa-warehouse me-2"></i>
                Gestão de Depósito
            </a>

            <?php if ($usuario_logado): ?>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">

                        <!-- Link 1: Painel -->
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($pagina_ativa == 'dashboard') ? 'active' : ''; ?>"
                                href="<?php echo $base_path; ?>dashboard.php">
                                <i class="fas fa-home me-1"></i> Painel
                            </a>
                        </li>

                        <!-- Link 2: Estoque (NOVO) -->
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($pagina_ativa == 'estoque') ? 'active' : ''; ?>"
                                href="<?php echo $base_path; ?>estoque/index.php">
                                <i class="fas fa-cubes me-1"></i> Estoque
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php echo ($pagina_ativa == 'validade') ? 'active' : ''; ?>"
                                href="<?php echo $base_path; ?>validade/index.php">
                                <i class="fas fa-calendar-alt me-1"></i> Validades
                            </a>
                        </li>

                        <!-- Link 3: Movimentações (Dropdown opcional ou links diretos) -->
                        <li class="nav-item dropdown <?php echo ($pagina_ativa == 'entrada' || $pagina_ativa == 'saida') ? 'active' : ''; ?>">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMov" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-exchange-alt me-1"></i> Movimentação
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="<?php echo $base_path; ?>movimentacao/grafico.php">
                                        <i class="fas fa-chart-line fa-fw me-2 text-primary"></i>Análise / Gráfico
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo $base_path; ?>entrada/index.php">
                                        <i class="fas fa-truck-loading fa-fw me-2 text-success"></i>Entradas
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo $base_path; ?>saida/index.php">
                                        <i class="fas fa-dolly fa-fw me-2 text-danger"></i>Saídas
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo $base_path; ?>ajuste/index.php">
                                        <i class="fas fa-trash-alt fa-fw me-2 text-danger"></i>Perdas/Ajustes
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Link 4: Cadastros -->
                        <li class="nav-item dropdown <?php echo ($pagina_ativa == 'cadastros' || $pagina_ativa == 'produtos') ? 'active' : ''; ?>">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownCadastros" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-folder-open me-1"></i> Cadastros
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="<?php echo $base_path; ?>produto/index.php">
                                        <i class="fas fa-boxes fa-fw me-2"></i>Produtos
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo $base_path; ?>sku/index.php">
                                        <i class="fas fa-barcode fa-fw me-2"></i>Gerenciar SKUs
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo $base_path; ?>categoria/index.php">
                                        <i class="fas fa-sitemap fa-fw me-2"></i>Categorias
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo $base_path; ?>fornecedor/index.php">
                                        <i class="fas fa-truck-moving fa-fw me-2"></i>Fornecedores
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo $base_path; ?>unidade/index.php">
                                        <i class="fas fa-balance-scale fa-fw me-2"></i>Unidades
                                    </a>
                                </li>

                                <?php if ($is_admin): ?>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="<?php echo $base_path; ?>usuario/index.php">
                                            <i class="fas fa-users-cog fa-fw me-2 text-danger"></i>Gerenciar Usuários
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="<?php echo $base_path; ?>admin/index.php">
                                            <i class="fas fa-tools fa-fw me-2 text-danger"></i>Ferramentas Adm
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>
                                <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?php echo $base_path; ?>logout.php">
                                        <i class="fas fa-sign-out-alt me-2"></i>Sair
                                    </a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>

        </div>
    </nav>
    <!-- O CONTEÚDO DA PÁGINA COMEÇA AQUI -->