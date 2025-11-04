<?php
// Inicia a sessão em todas as páginas
// session_status() verifica se a sessão JÁ não foi iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado
// isset() verifica se a 'mochila' da sessão TEM a chave 'user_id'
$usuario_logado = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- O título será definido em cada página -->
    <title><?php echo isset($page_title) ? $page_title : 'Gestão de Depósito'; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome (Ícones) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Google Fonts (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS Personalizado -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #FAFAFA; /* Fundo (Paleta Terra) */
        }
        .navbar-custom {
            background-color: #51423D; /* Header (Paleta Terra) */
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
    </style>
</head>
<body>

<!-- Barra de Navegação Principal -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?php echo $usuario_logado ? 'dashboard.php' : 'index.php'; ?>">
            <i class="fas fa-warehouse me-2"></i>
            Gestão de Depósito
        </a>
        
        <?php if ($usuario_logado): ?>
            <!-- Menu de Usuário Logado -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">Painel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="produtos.php">Produtos</a>
                    </li>
                    <!-- Adicione mais links aqui (Entrada, Saída, etc.) -->
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>
                            <?php echo htmlspecialchars($_SESSION['user_name']); // Mostra o nome do usuário ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i>Sair
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        <?php endif; ?>
        
    </div>
</nav>

