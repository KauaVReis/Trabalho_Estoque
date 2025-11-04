<?php
// Define o título e as variáveis de caminho para o header
$page_title = 'Cadastrar Produto';
$base_path = '../'; // Estamos 1 nível abaixo da raiz (public)
$pagina_ativa = 'produtos'; // Para o menu 'active'

// Inclui o cabeçalho (agora voltando 2 níveis)
include '../../templates/header.php';

// (Segurança) Se o usuário NÃO ESTIVER LOGADO, chuta ele para o index.php (na raiz)
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit(); // Para o script
}

// ---- LÓGICA DA PÁGINA ----
// 1. Inclui o arquivo de lógica de produtos (agora voltando 2 níveis)
require_once '../../src/products.php';

// 2. Busca as categorias para preencher o <select>
$lista_de_categorias = listarCategorias();
// ---- FIM DA LÓGICA ----
?>

<!-- CONTEÚDO DA PÁGINA -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <h1 class="display-6 text-marrom-escuro mb-4">
                <i class="fas fa-plus-circle me-3"></i>Novo Produto
            </h1>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4 p-md-5">
                    
                    <!-- Formulário aponta para 'processar.php' (na mesma pasta) -->
                    <form method="POST" action="processar.php">
                        
                        <!-- Campo Nome -->
                        <div class="mb-3">
                            <label for="nome" class="form-label fw-medium">Nome do Produto Base:</label>
                            <input type="text" class="form-control form-control-lg" 
                                   id="nome" name="nome"
                                   placeholder="Ex: Tinta Acrílica Fosca" required>
                        </div>

                        <!-- Campo Descrição -->
                        <div class="mb-3">
                            <label for="descricao" class="form-label fw-medium">Descrição:</label>
                            <textarea class="form-control" 
                                      id="descricao" name="descricao" 
                                      rows="3" 
                                      placeholder="Detalhes sobre o produto, aplicação, etc."></textarea>
                        </div>
                        
                        <!-- Campo Categoria (Select) -->
                        <div class="mb-4">
                            <label for="id_categoria" class="form-label fw-medium">Categoria:</label>
                            <select class="form-select form-select-lg" id="id_categoria" name="id_categoria" required>
                                <option value="" disabled selected>Selecione uma categoria...</option>
                                
                                <!-- Loop do PHP para preencher as categorias -->
                                <?php foreach ($lista_de_categorias as $categoria): ?>
                                    <option value="<?php echo $categoria['id']; ?>">
                                        <?php echo htmlspecialchars($categoria['nome']); ?>
                                    </option>
                                <?php endforeach; ?>
                                
                            </select>
                        </div>
                        
                        <hr class="my-4">

                        <!-- Botões de Ação -->
                        <div class="d-flex justify-content-end gap-2">
                            <!-- Link de Cancelar aponta para 'index.php' (na mesma pasta) -->
                            <a href="index.php" class="btn btn-lg btn-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-lg fw-medium" style="background-color: #38A169; color: white;">
                                <i class="fas fa-save me-2"></i>Salvar Produto
                            </button>
                        </div>

                    </form>

                </div> <!-- fim .card-body -->
            </div> <!-- fim .card -->

        </div> <!-- fim .col -->
    </div> <!-- fim .row -->
</div> <!-- fim .container -->

<?php
// Inclui o rodapé (agora voltando 2 níveis)
include '../../templates/footer.php';
?>
