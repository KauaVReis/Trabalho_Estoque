<?php
/*
 * LÓGICA DE PRODUTOS (VERSÃO SUPER SIMPLES E COMENTADA)
 * Funções para Cadastrar, Listar, Buscar, Atualizar e Excluir Produtos.
 */

// Inclui o arquivo de conexão
require_once __DIR__ . '/db.php';

/**
 * Busca TODAS as categorias do banco de dados.
 */
function listarCategorias() {
    $conn = getDBConnection();
    $sql = "SELECT id, nome FROM Categorias ORDER BY nome ASC";
    $resultado = mysqli_query($conn, $sql);
    
    $categorias = [];
    while ($linha = mysqli_fetch_assoc($resultado)) {
        $categorias[] = $linha; 
    }
    
    mysqli_close($conn);
    return $categorias;
}

/**
 * Busca TODOS os produtos do banco, já com o NOME da categoria.
 */
function listarProdutos() {
    $conn = getDBConnection();
    $sql = "SELECT p.*, c.nome AS categoria_nome 
            FROM Produtos p
            JOIN Categorias c ON p.id_categoria = c.id
            ORDER BY p.nome ASC";
            
    $resultado = mysqli_query($conn, $sql);
    
    $produtos = [];
    while ($linha = mysqli_fetch_assoc($resultado)) {
        $produtos[] = $linha;
    }
    
    mysqli_close($conn);
    return $produtos;
}

/**
 * Salva um novo produto no banco de dados.
 */
function cadastrarProduto($nome, $descricao, $id_categoria) {
    $conn = getDBConnection();
    $sql = "INSERT INTO Produtos (nome, descricao, id_categoria) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    // "ssi" = String, String, Integer
    mysqli_stmt_bind_param($stmt, "ssi", $nome, $descricao, $id_categoria);
    
    $sucesso = mysqli_stmt_execute($stmt);
    
    if ($sucesso) {
        require_once __DIR__ . '/logger.php';
        registrarLog('Produto Criado', ['nome' => $nome, 'categoria' => $id_categoria]);
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    return $sucesso;
}

// -----------------------------------------------------------------
// NOVAS FUNÇÕES ADICIONADAS (EDITAR E EXCLUIR)
// -----------------------------------------------------------------

/**
 * Busca UM ÚNICO produto pelo seu ID.
 * (Usado na página 'editar.php' para preencher o formulário)
 *
 * @param int $id O ID do produto a ser buscado
 * @return array|null Retorna os dados do produto ou null se não encontrar
 */
function buscarProdutoPorId($id) {
    $conn = getDBConnection();
    
    // 1. SQL com placeholder (?)
    $sql = "SELECT * FROM Produtos WHERE id = ? LIMIT 1";
    
    // 2. Prepara a query
    $stmt = mysqli_prepare($conn, $sql);
    
    // 3. Liga o ID
    // "i" = $id é um Integer (número)
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    // 4. Executa
    mysqli_stmt_execute($stmt);
    
    // 5. Pega o resultado
    $resultado = mysqli_stmt_get_result($stmt);
    
    // 6. Transforma em array
    $produto = mysqli_fetch_assoc($resultado);
    
    // 7. Fecha tudo
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    return $produto; // Retorna o produto (ou null se não achou)
}

/**
 * ATUALIZA um produto existente no banco.
 *
 * @param int $id O ID do produto a ser atualizado
 * @param string $nome O novo nome
 * @param string $descricao A nova descrição
 * @param int $id_categoria O novo ID da categoria
 *
 * @return bool Retorna true se atualizou, false se deu erro.
 */
function atualizarProduto($id, $nome, $descricao, $id_categoria) {
    $conn = getDBConnection();
    
    // 1. SQL de UPDATE com placeholders (?)
    $sql = "UPDATE Produtos SET nome = ?, descricao = ?, id_categoria = ? WHERE id = ?";
    
    // 2. Prepara
    $stmt = mysqli_prepare($conn, $sql);
    
    // 3. Liga as variáveis
    // "ssii" = String, String, Integer, Integer (os 4 parâmetros)
    mysqli_stmt_bind_param($stmt, "ssii", $nome, $descricao, $id_categoria, $id);
    
    // 4. Executa
    $sucesso = mysqli_stmt_execute($stmt);
    
    if ($sucesso) {
        require_once __DIR__ . '/logger.php';
        registrarLog('Produto Atualizado', ['id' => $id, 'novo_nome' => $nome]);
    }
    
    // 5. Fecha
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    return $sucesso;
}

/**
 * EXCLUI um produto do banco de dados.
 *
 * @param int $id O ID do produto a ser excluído
 * @return bool Retorna true se excluiu, false se deu erro.
 */
function excluirProduto($id) {
    $conn = getDBConnection();
    
    // 1. SQL de DELETE
    // ATENÇÃO: Isso pode falhar se houver LOTES ou SKUs ligados a este produto
    // (Depende de como o banco foi configurado com 'ON DELETE')
    $sql = "DELETE FROM Produtos WHERE id = ?";
    
    // 2. Prepara
    $stmt = mysqli_prepare($conn, $sql);
    
    // 3. Liga o ID
    // "i" = $id é um Integer
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    // 4. Executa
    $sucesso = mysqli_stmt_execute($stmt);
    
    if ($sucesso) {
        require_once __DIR__ . '/logger.php';
        registrarLog('Produto Excluído', ['id_excluido' => $id]);
    }
    
    // 5. Fecha
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    return $sucesso;
}
?>
