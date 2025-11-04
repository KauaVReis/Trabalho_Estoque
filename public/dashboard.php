<?php
// Define o título da página
$page_title = 'Painel Principal';

// Inclui o cabeçalho (que já tem o session_start())
include '../templates/header.php';

// (Segurança) Se o usuário NÃO ESTIVER LOGADO, chuta ele para o index.php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit(); // Para o script
}
?>

<!-- 2. Conteúdo Principal (Grid de Cartões) -->
<div class="container my-5">
    
    <!-- Título de Boas-Vindas (personalizado com o nome) -->
    <div class="text-center mb-5">
        <h1 class="display-6 text-marrom-escuro">
            Bem-vindo(a), <?php echo htmlspecialchars($_SESSION['user_name']); ?>!
        </h1>
        <p class="lead" style="color: #8F8577;">Escolha uma opção abaixo para iniciar a gestão.</p>
    </div>

    <!-- Grid de Ações (O CONTEÚDO ÚNICO DA PÁGINA) -->
    <!-- (Este é o HTML que você já tinha no 'main_dashboard.html') -->
    <div class="row g-4">
        
        <!-- Card 1: Nova Entrada (Laranja) -->
        <div class="col-lg-4 col-md-6">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 card-hover-action" style="background-color: #DD6B20; color: white;">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-truck-loading fa-3x mb-3"></i>
                        <h5 class="card-title fw-bold">Nova Entrada de Lote</h5>
                        <p class="card-text small">Registrar recebimento de materiais.</p>
                    </div>
                </div>
            </a>
        </div>
        
        <!-- Card 2: Registrar Saída (Verde) -->
        <div class="col-lg-4 col-md-6">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 card-hover-action" style="background-color: #38A169; color: white;">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                        <h5 class="card-title fw-bold">Registrar Saída (Venda)</h5>
                        <p class="card-text small">Dar baixa no estoque por venda ou uso.</p>
                    </div>
                </div>
            </a>
        </div>
        
        <!-- Card 3: Consultar Estoque (Azul) -->
        <div class="col-lg-4 col-md-6">
            <a href="produtos.php" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 card-hover-action" style="background-color: #005A9C; color: white;">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-boxes fa-3x mb-3"></i>
                        <h5 class="card-title fw-bold">Consultar Estoque</h5>
                        <p class="card-text small">Ver inventário e produtos.</p>
                    </div>
                </div>
            </a>
        </div>
        
        <!-- ... (Outros cards) ... -->

    </div> <!-- Fim do .row -->

</div> <!-- Fim do .container -->

<!-- CSS Adicional para os cards (pode mover para um .css) -->
<style>
    .card-hover-action {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card-hover-action:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }
</style>

<?php
// Inclui o rodapé
include '../templates/footer.php';
?>

