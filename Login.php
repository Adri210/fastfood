<?php
session_start();
include('conexao.php');

// Exibir mensagem de sucesso do cadastro
if (isset($_SESSION['sucesso'])) {
    echo "<script>alert('" . $_SESSION['sucesso'] . "');</script>";
    unset($_SESSION['sucesso']); // Limpa a mensagem da sessão após exibir
}

// Processar o formulário de login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Buscar o usuário no banco de dados
    $sql = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['email'] = $usuario['email'];
        header("Location: home.php"); 
        exit();
    } else {
        echo "Email ou senha inválidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../fastfood/styles/Login.css">
    <title>Login</title>
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
                <h2>LOGIN</h2>
                <input type="email" name="email" placeholder="Email" required>
                <br>
                <input type="password" name="senha" placeholder="Senha" required>
                <br>    
                <button type="submit" name="acao" value="Entrar">Entrar</button>
                <a href="./cadastro.php">Não tenho cadastro</a>
            </div>
        </div>
    </div>
</form>
</body>
</html>