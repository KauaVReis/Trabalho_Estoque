<?php
$page_title = 'Novo Fornecedor';
$base_path = '../'; 
$pagina_ativa = 'cadastros'; 

include '../../templates/header.php';

// Segurança
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit(); 
}
?>

<!-- Conteúdo da Página -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <h1 class="display-6 text-marrom-escuro mb-4">
                <i class="fas fa-plus-circle me-3"></i>Novo Fornecedor
            </h1>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4 p-md-5">
                    
                    <!-- Formulário aponta para o processar.php -->
                    <form method="POST" action="processar.php">
                        
                        <!-- Linha 1: Razão Social e CNPJ -->
                        <div class="row mb-3">
                            <div class="col-md-7">
                                <label for="razao_social" class="form-label fw-medium">Razão Social:</label>
                                <input type="text" class="form-control form-control-lg" 
                                       id="razao_social" name="razao_social"
                                       placeholder="Nome da Empresa" required>
                            </div>
                            <div class="col-md-5">
                                <label for="cnpj" class="form-label fw-medium">CNPJ:</label>
                                <input type="text" class="form-control form-control-lg" 
                                       id="cnpj" name="cnpj"
                                       placeholder="XX.XXX.XXX/0001-XX">
                            </div>
                        </div>

                        <!-- Linha 2: Contato -->
                        <div class="row mb-4">
                            <div class="col-md-7">
                                <label for="contato_nome" class="form-label fw-medium">Nome do Contato:</label>
                                <input type="text" class="form-control form-control-lg" 
                                       id="contato_nome" name="contato_nome"
                                       placeholder="Nome do vendedor ou representante">
                            </div>
                            <div class="col-md-5">
                                <label for="contato_telefone" class="form-label fw-medium">Telefone:</label>
                                <input type="text" class="form-control form-control-lg" 
                                       id="contato_telefone" name="contato_telefone"
                                       placeholder="(XX) XXXXX-XXXX">
                            </div>
                        </div>
                        
                        <hr class="my-4">

                        <!-- Botões -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php" class="btn btn-lg btn-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-lg fw-medium" style="background-color: #38A169; color: white;">
                                <i class="fas fa-save me-2"></i>Salvar
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