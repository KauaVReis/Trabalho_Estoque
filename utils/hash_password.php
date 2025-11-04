<?php
// --- FERRAMENTA PARA CRIAR HASH DE SENHA ---
// Use isso UMA VEZ para criar seu usuário admin no banco.
// Depois, APAGUE ESTE ARQUIVO.

// 1. Defina a senha que você quer usar
$senha_admin = '211207'; // Mude para sua senha

// 2. Gera o hash seguro
$hash = password_hash($senha_admin, PASSWORD_DEFAULT);

// 3. Mostra a query SQL pronta para você copiar e colar
echo "<h2>Query para criar seu usuário Admin:</h2>";
echo "<p>Copie e cole o código abaixo no seu MySQL (phpMyAdmin, etc.)</p>";
echo "<hr>";
echo "<pre style='background:#eee; padding:15px; border-radius:5px; font-family:monospace; font-size:1.1em;'>";

// (Ajuste o email e nome se quiser)
echo "INSERT INTO Usuarios (nome, login, hash_senha, cargo) 
VALUES (
    'Ian', 
    '47805206813', 
    '{$hash}', 
    'admin'
);";
    
echo "</pre>";

echo "<hr>";
echo "<p><strong>IMPORTANTE:</strong> Depois de rodar a query no banco, <strong>APAGUE ESTE ARQUIVO (hash_password.php)</strong> da sua pasta 'utils' por segurança.</p>";
?>

