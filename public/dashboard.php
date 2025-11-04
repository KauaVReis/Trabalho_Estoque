<?php
// Define o título e as variáveis de caminho para o header
$page_title = 'Painel Principal';
$base_path = ''; // Estamos na raiz (public/)
$pagina_ativa = 'dashboard'; // Para o menu 'active'

// Inclui o cabeçalho (voltando 1 nível)
include '../templates/header.php';

// (Segurança) Se o usuário NÃO ESTIVER LOGADO, chuta ele para o index.php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Já estamos no index, mas por via das dúvidas
    exit(); // Para o script
}
?>

<!-- CONTEÚDO DA PÁGINA -->
<div class="container my-5">
    
    <!-- Título de Boas-Vindas -->
    <div class="text-center mb-5">
        <h1 class="display-6 text-marrom-escuro">Bem-vindo ao Sistema, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
        <p class="lead" style="color: #8F8577;">Escolha uma opção abaixo para iniciar a gestão.</p>
    </div>

    <!-- Grid de Ações -->
    <div class="row g-4 justify-content-center">
        
        <!-- Card 1: Nova Entrada (Laranja) -->
        <div class="col-lg-3 col-md-5">
            <a href="#" class="card card-hover text-white text-decoration-none h-100" style="background-color: #DD6B20;">
                <div class="card-body text-center p-4">
                    <i class="fas fa-truck-loading fa-3x mb-3"></i>
                    <h5 class="card-title fw-bold">Nova Entrada</h5>
                    <p class="card-text small">Registrar lotes de compra.</p>
                </div>
            </a>
        </div>

        <!-- Card 2: Nova Saída (Verde) -->
        <div class="col-lg-3 col-md-5">
            <a href="#" class="card card-hover text-white text-decoration-none h-100" style="background-color: #38A169;">
                <div class="card-body text-center p-4">
                    <i class="fas fa-dolly fa-3x mb-3"></i>
                    <h5 class="card-title fw-bold">Registrar Saída</h5>
                    <p class="card-text small">Dar baixa por venda ou uso.</p>
                </div>
            </a>
        </div>
        
        <!-- Card 3: Consultar Estoque (Azul) -->
        <div class="col-lg-3 col-md-5">
            <a href="#" class="card card-hover text-white text-decoration-none h-100" style="background-color: #005A9C;">
                <div class="card-body text-center p-4">
                    <i class="fas fa-search-location fa-3x mb-3"></i>
                    <h5 class="card-title fw-bold">Consultar Estoque</h5>
                    <p class="card-text small">Ver lotes e validades.</p>
                </div>
            </a>
        </div>

        <!-- Card 4: Alertas (Amarelo) -->
        <div class="col-lg-3 col-md-5">
            <a href="#" class="card card-hover text-dark text-decoration-none h-100" style="background-color: #ECC94B;">
                <div class="card-body text-center p-4">
                    <i class="fas fa-calendar-times fa-3x mb-3"></i>
                    <h5 class="card-title fw-bold">Alertas de Validade</h5>
                    <p class="card-text small">Lotes próximos do vencimento.</p>
                </div>
            </a>
        </div>
        
        <!-- Card 5: Estoque Baixo (Vermelho) -->
        <div class="col-lg-3 col-md-5">
            <a href="#" class="card card-hover text-white text-decoration-none h-100" style="background-color: #E53E3E;">
                <div class="card-body text-center p-4">
                    <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                    <h5 class="card-title fw-bold">Estoque Baixo</h5>
                    <p class="card-text small">Itens que precisam de reposição.</p>
                </div>
            </a>
        </div>
        
        <!-- Card 6: Produtos (Cinza) -->
        <div class="col-lg-3 col-md-5">
            <!-- ATUALIZADO: Link para o novo módulo -->
            <a href="produto/index.php" class="card card-hover text-white text-decoration-none h-100" style="background-color: #8F8577;">
                <div class="card-body text-center p-4">
                    <i class="fas fa-tags fa-3x mb-3"></i>
                    <h5 class="card-title fw-bold">Produtos</h5>
                    <p class="card-text small">Gerenciar produtos base.</p>
                </div>
            </a>
        </div>

    </div> <!-- Fim do .row -->

</div> <!-- Fim do .container -->

<?php
// Inclui o rodapé
include '../templates/footer.php';
?>

