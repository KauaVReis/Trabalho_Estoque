<?php
$page_title = 'Unidades de Medida';
$base_path = '../'; 
$pagina_ativa = 'cadastros'; 

include '../../templates/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); 
    exit(); 
}

require_once '../../src/units.php';
$lista_unidades = listarUnidades();
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6 text-marrom-escuro">
            <i class="fas fa-balance-scale me-3"></i>Unidades de Medida
        </h1>
        <a href="novo.php" class="btn btn-lg fw-medium" style="background-color: #DD6B20; color: white;">
            <i class="fas fa-plus me-2"></i>Nova Unidade
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 10%;">ID</th>
                        <th style="width: 50%;">Nome</th>
                        <th style="width: 20%;">Sigla</th>
                        <th style="width: 20%;" class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_unidades as $u): ?>
                    <tr>
                        <th><?php echo $u['id']; ?></th>
                        <td><?php echo htmlspecialchars($u['nome']); ?></td>
                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($u['sigla']); ?></span></td>
                        <td class="text-center">
                            <a href="editar.php?id=<?php echo $u['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-pencil-alt"></i></a>
                            <a href="excluir.php?id=<?php echo $u['id']; ?>" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($lista_unidades)): ?>
                        <tr><td colspan="4" class="text-center text-muted p-4">Nenhuma unidade cadastrada.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div> 
</div> 

<?php include '../../templates/footer.php'; ?>