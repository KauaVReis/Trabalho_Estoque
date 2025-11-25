<?php
// 1. Inicia sessão e verifica segurança NO TOPO
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// 2. Inclui a lógica de estatísticas
require_once '../src/dashboard_stats.php';

// 3. Busca os dados
$lotes_vencendo = contarLotesVencendo(30);
$itens_baixo_estoque = contarEstoqueBaixo();
$movimentacoes = contarMovimentacoesHoje();

// 4. Configurações da página
$page_title = 'Painel Principal';
$base_path = ''; 
$pagina_ativa = 'dashboard'; 

// 5. Carrega o HTML
include '../templates/header.php';
?>

<!-- CONTEÚDO DA PÁGINA -->
<div class="container my-5">
    
    <!-- Título de Boas-Vindas -->
    <div class="text-center mb-5">
        <h1 class="display-6 text-marrom-escuro">
            Bem-vindo(a), <?php echo htmlspecialchars($_SESSION['user_name']); ?>!
        </h1>
        <p class="lead" style="color: #8F8577;">Visão geral do seu estoque hoje.</p>
    </div>

    <!-- Grid de Ações -->
    <div class="row g-4 justify-content-center">
        
        <!-- Card 1: Nova Entrada (Laranja) -->
        <div class="col-lg-3 col-md-5">
            <a href="entrada/nova.php" class="card card-hover text-white text-decoration-none h-100" style="background-color: #DD6B20;">
                <div class="card-body text-center p-4">
                    <i class="fas fa-truck-loading fa-3x mb-3"></i>
                    <h5 class="card-title fw-bold">Nova Entrada</h5>
                    <p class="card-text small">
                        <?php echo $movimentacoes['entradas'] > 0 ? $movimentacoes['entradas'] . ' hoje' : 'Registrar compra'; ?>
                    </p>
                </div>
            </a>
        </div>

        <!-- Card 2: Nova Saída (Verde) -->
        <div class="col-lg-3 col-md-5">
            <a href="saida/nova.php" class="card card-hover text-white text-decoration-none h-100" style="background-color: #38A169;">
                <div class="card-body text-center p-4">
                    <i class="fas fa-dolly fa-3x mb-3"></i>
                    <h5 class="card-title fw-bold">Registrar Saída</h5>
                    <p class="card-text small">
                        <?php echo $movimentacoes['saidas'] > 0 ? $movimentacoes['saidas'] . ' hoje' : 'Registrar venda'; ?>
                    </p>
                </div>
            </a>
        </div>
        
        <!-- Card 3: Consultar Estoque (Azul) -->
        <div class="col-lg-3 col-md-5">
            <a href="estoque/index.php" class="card card-hover text-white text-decoration-none h-100" style="background-color: #005A9C;">
                <div class="card-body text-center p-4">
                    <i class="fas fa-search-location fa-3x mb-3"></i>
                    <h5 class="card-title fw-bold">Consultar Estoque</h5>
                    <p class="card-text small">Ver saldos e lotes.</p>
                </div>
            </a>
        </div>

        <!-- Card 4: Alertas de Validade (Amarelo) -->
        <div class="col-lg-3 col-md-5">
            <a href="estoque/index.php" class="card card-hover text-dark text-decoration-none h-100" style="background-color: #ECC94B;">
                <div class="card-body text-center p-4 position-relative">
                    
                    <?php if ($lotes_vencendo > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo $lotes_vencendo; ?>
                            <span class="visually-hidden">alertas</span>
                        </span>
                    <?php endif; ?>

                    <i class="fas fa-calendar-times fa-3x mb-3"></i>
                    <h5 class="card-title fw-bold">Vencendo em 30d</h5>
                    <p class="card-text small fw-bold">
                        <?php echo $lotes_vencendo > 0 ? "$lotes_vencendo lote(s) crítico(s)" : "Nenhum lote crítico"; ?>
                    </p>
                </div>
            </a>
        </div>
        
        <!-- Card 5: Estoque Baixo (Vermelho) -->
        <div class="col-lg-3 col-md-5">
            <a href="estoque/index.php" class="card card-hover text-white text-decoration-none h-100" style="background-color: #E53E3E;">
                <div class="card-body text-center p-4 position-relative">
                    
                    <?php if ($itens_baixo_estoque > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">
                            <?php echo $itens_baixo_estoque; ?>
                        </span>
                    <?php endif; ?>

                    <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                    <h5 class="card-title fw-bold">Estoque Baixo</h5>
                    <p class="card-text small fw-bold">
                        <?php echo $itens_baixo_estoque > 0 ? "$itens_baixo_estoque produto(s) baixo(s)" : "Tudo abastecido"; ?>
                    </p>
                </div>
            </a>
        </div>
        
        <!-- Card 6: Produtos (Cinza) -->
        <div class="col-lg-3 col-md-5">
            <a href="produto/index.php" class="card card-hover text-white text-decoration-none h-100" style="background-color: #8F8577;">
                <div class="card-body text-center p-4">
                    <i class="fas fa-tags fa-3x mb-3"></i>
                    <h5 class="card-title fw-bold">Produtos</h5>
                    <p class="card-text small">Gerenciar cadastros.</p>
                </div>
            </a>
        </div>

    </div> <!-- Fim do .row -->

</div> <!-- Fim do .container -->

<?php
include '../templates/footer.php';
?>