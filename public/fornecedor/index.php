<?php
$page_title = 'Fornecedores';
$base_path = '../'; 
$pagina_ativa = 'cadastros'; 

include '../../templates/header.php';

// Segurança: Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); 
    exit(); 
}

// Lógica: Busca os fornecedores no banco
require_once '../../src/fornecedores.php';
$lista_de_fornecedores = listarFornecedores();
?>

<!-- Conteúdo da Página -->
<div class="container my-5">
    
    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6 text-marrom-escuro">
            <i class="fas fa-truck-moving me-3"></i>Fornecedores
        </h1>
        <a href="novo.php" class="btn btn-lg fw-medium" style="background-color: #DD6B20; color: white;">
            <i class="fas fa-plus me-2"></i>Novo Fornecedor
        </a>
    </div>

    <!-- Tabela -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
            
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 35%;">Razão Social</th>
                        <th scope="col" style="width: 15%;">CNPJ</th>
                        <th scope="col" style="width: 20%;">Contato</th>
                        <th scope="col" style="width: 15%;">Telefone</th>
                        <th scope="col" style="width: 15%;" class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php foreach ($lista_de_fornecedores as $fornecedor): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($fornecedor['razao_social']); ?></td>
                        <td><?php echo htmlspecialchars($fornecedor['cnpj']); ?></td>
                        <td><?php echo htmlspecialchars($fornecedor['contato_nome']); ?></td>
                        <td><?php echo htmlspecialchars($fornecedor['contato_telefone']); ?></td>
                        <td class="text-center">
                            <a href="editar.php?id=<?php echo $fornecedor['id']; ?>" 
                               class="btn btn-sm btn-warning" title="Editar">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <a href="excluir.php?id=<?php echo $fornecedor['id']; ?>" 
                               class="btn btn-sm btn-danger" title="Excluir">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <?php if (empty($lista_de_fornecedores)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted p-4">
                                Nenhum fornecedor cadastrado ainda.
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