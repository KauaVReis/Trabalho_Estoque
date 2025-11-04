<?php
$page_title = 'Confirmar Exclusão';
$base_path = '../'; 
$pagina_ativa = 'produtos'; 

include '../../templates/header.php';

// (Segurança) Se o usuário NÃO ESTIVER LOGADO
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit(); 
}

// ---- LÓGICA DA PÁGINA ----
require_once '../../src/products.php';

// 1. Pega o ID da URL
$id_produto = (int)$_GET['id'];
if ($id_produto == 0) {
    header("Location: index.php");
    exit();
}

// 2. Verifica se o usuário CLICOU EM "CONFIRMAR"
// (O link de confirmar vai ter "?id=5&confirm=1")
$confirmado = isset($_GET['confirm']) && $_GET['confirm'] == '1';

if ($confirmado) {
    // --- AÇÃO DE EXCLUIR ---
    
    // 3. Tenta excluir o produto
    $sucesso = excluirProduto($id_produto);
    
    // 4. Redireciona de volta para a lista (com ou sem erro)
    header("Location: index.php");
    exit();
    
} else {
    // --- MOSTRAR PÁGINA DE CONFIRMAÇÃO ---
    
    // 3. Busca os dados do produto (só para mostrar o nome)
    $produto = buscarProdutoPorId($id_produto);
    
    // 4. Se não existir, manda embora
    if (!$produto) {
        header("Location: index.php");
        exit();
    }
}
// ---- FIM DA LÓGICA ----
?>

<!-- CONTEÚDO DA PÁGINA (SÓ MOSTRA SE NÃO FOI CONFIRMADO) -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            
            <div class="card shadow-lg border-2 border-danger">
                <div class="card-body p-4 p-md-5 text-center">
                    
                    <i class="fas fa-exclamation-triangle fa-4x text-danger mb-4"></i>

                    <h1 class="h3 fw-bold text-marrom-escuro mb-3">Confirmar Exclusão</h1>
                    
                    <p class="lead mb-4">
                        Você tem certeza que deseja excluir permanentemente o produto:
                    </p>
                    
                    <!-- Mostra o nome do produto -->
                    <h3 class="fw-bold text-danger bg-light p-3 rounded">
                        <?php echo htmlspecialchars($produto['nome']); ?>
                        (ID: <?php echo $produto['id']; ?>)
                    </h3>
                    
                    <p class="text-muted mt-4">
                        Esta ação não pode ser desfeita. 
                        Isso pode apagar permanentemente todos os SKUs e Lotes associados.
                    </p>

                    <hr class="my-4">

                    <!-- Botões de Ação -->
                    <div class="d-flex justify-content-center gap-3">
                        <a href="index.php" class="btn btn-lg btn-secondary">
                            <i class="fas fa-times me-2"></i>
                            Cancelar
                        </a>
                        
                        <!-- 
                          Link de Confirmação:
                          Aponta para ESTA MESMA PÁGINA, mas adiciona o "&confirm=1"
                        -->
                        <a href="excluir.php?id=<?php echo $produto['id']; ?>&confirm=1" 
                           class="btn btn-lg btn-danger">
                            <i class="fas fa-trash-alt me-2"></i>
                            Sim, Excluir
                        </a>
                    </div>

                </div> <!-- fim .card-body -->
            </div> <!-- fim .card -->

        </div> <!-- fim .col -->
    </div> <!-- fim .row -->
</div> <!-- fim .container -->

<?php
include '../../templates/footer.php';
?>
