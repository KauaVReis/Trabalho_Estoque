<?php
$page_title = 'Editar Fornecedor';
$base_path = '../'; 
$pagina_ativa = 'cadastros'; 

include '../../templates/header.php';

// Segurança
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit(); 
}

// Lógica: Buscar o fornecedor que será editado
require_once '../../src/fornecedores.php';

// 1. Pega o ID da URL (editar.php?id=...)
$id_fornecedor = (int)$_GET['id'];
if ($id_fornecedor == 0) {
    header("Location: index.php");
    exit();
}

// 2. Busca os dados no banco
$fornecedor = buscarFornecedorPorId($id_fornecedor);
if (!$fornecedor) {
    header("Location: index.php");
    exit();
}
?>

<!-- Conteúdo da Página -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <h1 class="display-6 text-marrom-escuro mb-4">
                <i class="fas fa-pencil-alt me-3"></i>Editar Fornecedor
            </h1>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4 p-md-5">
                    
                    <form method="POST" action="processar.php">
                        
                        <!-- CAMPO OCULTO (HIDDEN) com o ID -->
                        <input type="hidden" name="id" value="<?php echo $fornecedor['id']; ?>">

                        <!-- Linha 1: Razão Social e CNPJ -->
                        <div class="row mb-3">
                            <div class="col-md-7">
                                <label for="razao_social" class="form-label fw-medium">Razão Social:</label>
                                <input type="text" class="form-control form-control-lg" 
                                       id="razao_social" name="razao_social"
                                       value="<?php echo htmlspecialchars($fornecedor['razao_social']); ?>" required>
                            </div>
                            <div class="col-md-5">
                                <label for="cnpj" class="form-label fw-medium">CNPJ:</label>
                                <input type="text" class="form-control form-control-lg" 
                                       id="cnpj" name="cnpj"
                                       value="<?php echo htmlspecialchars($fornecedor['cnpj']); ?>">
                            </div>
                        </div>

                        <!-- Linha 2: Contato -->
                        <div class="row mb-4">
                            <div class="col-md-7">
                                <label for="contato_nome" class="form-label fw-medium">Nome do Contato:</label>
                                <input type="text" class="form-control form-control-lg" 
                                       id="contato_nome" name="contato_nome"
                                       value="<?php echo htmlspecialchars($fornecedor['contato_nome']); ?>">
                            </div>
                            <div class="col-md-5">
                                <label for="contato_telefone" class="form-label fw-medium">Telefone:</label>
                                <input type="text" class="form-control form-control-lg" 
                                       id="contato_telefone" name="contato_telefone"
                                       value="<?php echo htmlspecialchars($fornecedor['contato_telefone']); ?>">
                            </div>
                        </div>
                        
                        <hr class="my-4">

                        <!-- Botões -->
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cnpjInput = document.getElementById('cnpj');
    const phoneInput = document.getElementById('contato_telefone');

    if (cnpjInput) {
        // Dispara o evento input ao carregar para formatar o valor inicial
        if (cnpjInput.value) {
            cnpjInput.dispatchEvent(new Event('input'));
        }

        cnpjInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length > 14) value = value.slice(0, 14);

            // Máscara CNPJ: 00.000.000/0000-00
            if (value.length > 12) {
                value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2}).*/, '$1.$2.$3/$4-$5');
            } else if (value.length > 8) {
                value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d{0,4}).*/, '$1.$2.$3/$4');
            } else if (value.length > 5) {
                value = value.replace(/^(\d{2})(\d{3})(\d{0,3}).*/, '$1.$2.$3');
            } else if (value.length > 2) {
                value = value.replace(/^(\d{2})(\d{0,3}).*/, '$1.$2');
            }
            
            e.target.value = value;
        });
        
        // Força formatação inicial se tiver valor
        if (cnpjInput.value) {
             let value = cnpjInput.value.replace(/\D/g, '');
             if (value.length > 12) {
                value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2}).*/, '$1.$2.$3/$4-$5');
             }
             cnpjInput.value = value;
        }
    }

    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length > 11) value = value.slice(0, 11);

            // Máscara Telefone: (00) 00000-0000
            if (value.length > 10) {
                value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
            } else if (value.length > 6) {
                value = value.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
            } else if (value.length > 2) {
                value = value.replace(/^(\d{2})(\d{0,5}).*/, '($1) $2');
            } else if (value.length > 0) {
                value = value.replace(/^(\d{0,2}).*/, '($1');
            }
            
            e.target.value = value;
        });
        
        // Força formatação inicial se tiver valor
        if (phoneInput.value) {
            let value = phoneInput.value.replace(/\D/g, '');
            if (value.length > 10) {
                value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
            } else if (value.length > 6) {
                value = value.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
            }
            phoneInput.value = value;
        }
    }
});
</script>

<?php
include '../../templates/footer.php';
?>