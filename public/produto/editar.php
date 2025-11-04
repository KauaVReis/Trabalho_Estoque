<?php
$page_title = 'Editar Produto';
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

// 1. Pega o ID do produto da URL (ex: editar.php?id=5)
$id_produto = (int)$_GET['id']; // (int) força a ser um número

// 2. Se não tiver ID, ou for 0, manda embora
if ($id_produto == 0) {
    header("Location: index.php");
    exit();
}

// 3. Busca os dados ATUAIS do produto no banco
$produto = buscarProdutoPorId($id_produto);

// 4. Se o produto não existir (ex: ID 999), manda embora
if (!$produto) {
    header("Location: index.php");
    exit();
}

// 5. Busca as categorias (para o <select>)
$lista_de_categorias = listarCategorias();
// ---- FIM DA LÓGICA ----
?>

<!-- CONTEÚDO DA PÁGINA -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <h1 class="display-6 text-marrom-escuro mb-4">
                <i class="fas fa-pencil-alt me-3"></i>Editar Produto
            </h1>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4 p-md-5">

                    <!-- Formulário aponta para o 'processar.php' -->
                    <form method="POST" action="processar.php">

                        <!-- 
                          CAMPO OCULTO (HIDDEN)
                          ESSENCIAL: Manda o ID para o 'processar.php' 
                          saber que é uma ATUALIZAÇÃO (UPDATE) e não um cadastro novo.
                        -->
                        <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">

                        <!-- Campo Nome (preenchido com dados do banco) -->
                        <div class="mb-3">
                            <label for="nome" class="form-label fw-medium">Nome do Produto Base:</label>
                            <input type="text" class="form-control form-control-lg"
                                id="nome" name="nome"
                                value="<?php echo htmlspecialchars($produto['nome']); ?>"
                                required>
                        </div>

                        <!-- Campo Descrição (preenchido com dados do banco) -->
                        <div class="mb-3">
                            <label for="descricao" class="form-label fw-medium">Descrição:</label>
                            <textarea class="form-control"
                                id="descricao" name="descricao"
                                rows="3"><?php echo htmlspecialchars($produto['descricao']); ?></textarea>
                        </div>

                        <!-- Campo Categoria (Select) -->
                        <div class="mb-4">
                            <label for="id_categoria" class="form-label fw-medium">Categoria:</label>
                            <select class="form-select form-select-lg" id="id_categoria" name="id_categoria" required>
                                <option value="" disabled>Selecione uma categoria...</option>

                                <?php foreach ($lista_de_categorias as $categoria): ?>
                                    <!-- 
                                      LÓGICA DO 'SELECTED'
                                      Se o ID da categoria (do loop) for IGUAL ao 
                                      ID da categoria do produto (do banco),
                                      então marca este <option> como 'selected'.
                                    -->
                                    <option
                                        value="<?php echo $categoria['id']; ?>"
                                        <?php echo ($categoria['id'] == $produto['id_categoria']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($categoria['nome']); ?>
                                    </option>
                                <?php endforeach; ?>

                            </select>
                        </div>

                        <hr class="my-4">

                        <!-- Botões de Ação -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php" class="btn btn-lg btn-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-lg fw-medium" style="background-color: #38A169; color: white;">
                                <i class="fas fa-save me-2"></i>Salvar Alterações
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<?php
include '../../templates/footer.php';
?>