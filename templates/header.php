<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$usuario_logado = isset($_SESSION['user_id']);

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
    
    <!-- CSS Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #FAFAFA; }
        .navbar-custom { background-color: #51423D; }
        .text-marrom-escuro { color: #51423D; }
        .btn-login { background-color: #51423D; border-color: #51423D; color: white; }
        .btn-login:hover { background-color: #3a2f2c; border-color: #3a2f2c; }
        
        /* Ajuste para o link ativo (tanto o link direto quanto o dropdown) */
        .navbar-nav .nav-link.active,
        .navbar-nav .nav-item.active .nav-link {
            font-weight: 600;
        }
    </style>
</head>
<body>

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
                    
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($pagina_ativa == 'dashboard') ? 'active' : ''; ?>" 
                           href="<?php echo $base_path; ?>dashboard.php">Painel</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($pagina_ativa == 'produtos') ? 'active' : ''; ?>" 
                           href="<?php echo $base_path; ?>produto/index.php">Produtos</a>
                    </li>
                    
                    <!-- 
                      NOVO MENU DROPDOWN "CADASTROS"
                      Adicionamos a classe 'active' ao <li> se a $pagina_ativa for 'cadastros'
                    -->
                    <li class="nav-item dropdown <?php echo ($pagina_ativa == 'cadastros') ? 'active' : ''; ?>">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownCadastros" role="button" data-bs-toggle="dropdown">
                            Cadastros
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="<?php echo $base_path; ?>categoria/index.php">
                                    <i class="fas fa-sitemap fa-fw me-2"></i>Categorias
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-truck-moving fa-fw me-2"></i>Fornecedores (Em breve)
                                </a>
                            </li>
                             <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-balance-scale fa-fw me-2"></i>Unidades (Em breve)
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- FIM DO NOVO MENU -->
                    
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

