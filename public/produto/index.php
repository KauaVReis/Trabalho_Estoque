<?php
$page_title = 'Lista de Produtos';
$base_path = '../'; 
$pagina_ativa = 'produtos'; 

include '../../templates/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); 
    exit(); 
}

require_once '../../src/products.php';
$lista_de_produtos = listarProdutos();
?>

<!-- CONTEÚDO DA PÁGINA -->
<div class="container my-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6 text-marrom-escuro">
            <i class="fas fa-boxes me-3"></i>Gerenciador de Produtos
        </h1>
        <a href="novo.php" class="btn btn-lg fw-medium" style="background-color: #DD6B20; color: white;">
            <i class="fas fa-plus me-2"></i>Adicionar Novo Produto
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
            
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nome do Produto</th>
                        <th scope="col">Categoria</th>
                        <th scope="col" class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php foreach ($lista_de_produtos as $produto): ?>
                    <tr>
                        <th scope="row"><?php echo $produto['id']; ?></th>
                        <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                        <td><?php echo htmlspecialchars($produto['categoria_nome']); ?></td>
                        <td class="text-center">
                            
                            <!-- ATUALIZADO: Link de Editar -->
                            <a href="editar.php?id=<?php echo $produto['id']; ?>" 
                               class="btn btn-sm btn-warning" title="Editar">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            
                            <!-- ATUALIZADO: Link de Excluir -->
                            <a href="excluir.php?id=<?php echo $produto['id']; ?>" 
                               class="btn btn-sm btn-danger" title="Excluir">
                                <i class="fas fa-trash-alt"></i>
                            </a>

                        </td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <?php if (empty($lista_de_produtos)): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted p-4">
                                Nenhum produto cadastrado ainda.
                            </td>
                        </tr>
                    <?php endif; ?>

                </tbody>
            </table>

        </div>
    </div> 

</div> 

<?php
include '../../templates/footer.php';
?>

