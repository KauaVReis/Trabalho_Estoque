<?php
$page_title = 'Categorias';
$base_path = '../'; 
$pagina_ativa = 'cadastros'; // Para o menu 'active'

include '../../templates/header.php';

// Segurança: Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); 
    exit(); 
}

// Lógica: Busca as categorias no banco
require_once '../../src/categories.php';
$lista_de_categorias = listarCategorias();
?>

<!-- Conteúdo da Página -->
<div class="container my-5">
    
    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6 text-marrom-escuro">
            <i class="fas fa-sitemap me-3"></i>Categorias
        </h1>
        <a href="novo.php" class="btn btn-lg fw-medium" style="background-color: #DD6B20; color: white;">
            <i class="fas fa-plus me-2"></i>Nova Categoria
        </a>
    </div>

    <!-- Tabela -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
            
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 10%;">ID</th>
                        <th scope="col" style="width: 70%;">Nome</th>
                        <th scope="col" style="width: 20%;" class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php foreach ($lista_de_categorias as $categoria): ?>
                    <tr>
                        <th scope="row"><?php echo $categoria['id']; ?></th>
                        <td><?php echo htmlspecialchars($categoria['nome']); ?></td>
                        <td class="text-center">
                            <a href="editar.php?id=<?php echo $categoria['id']; ?>" 
                               class="btn btn-sm btn-warning" title="Editar">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <a href="excluir.php?id=<?php echo $categoria['id']; ?>" 
                               class="btn btn-sm btn-danger" title="Excluir">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <?php if (empty($lista_de_categorias)): ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted p-4">
                                Nenhuma categoria cadastrada ainda.
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
