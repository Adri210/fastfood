<?php
session_start();
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);

    // Verificar se o email já existe
    $sql = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        echo "Email já cadastrado.";
    } else {
        // Inserir novo usuário
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->execute();

        
        $_SESSION['sucesso'] = "Cadastro realizado com sucesso! Faça login para continuar.";

        // Redirecionar para a página de login
        header("Location: Login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../fastfood/styles/Login.css">
    <title>Cadastro</title>
</head>
<body>
<header>
    <div class="logo">
        <h1>Fast Food</h1>
    </div>
</header>

<form action="" method="POST">
    <div class="wrapper">
        <div class="container">
            <div class="login">
                <h2>CADASTRO</h2>
                <input type="text" name="nome" placeholder="Nome" required>
                <br>
                <input type="email" name="email" placeholder="Email" required>
                <br>
                <input type="password" name="senha" placeholder="Senha" required>
                <br>    
                <button type="submit" name="acao" value="Cadastrar">Cadastrar</button>
                <a href="./Login.php">Já tenho cadastro</a>
            </div>
        </div>
    </div>
</form>
</body>
</html>