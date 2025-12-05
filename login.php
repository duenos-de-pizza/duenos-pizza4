<?php
session_start();

if (isset($_SESSION['idCli'])) {
    $_SESSION['msg'] = "Você já está logado!";
    header("Location: index.php");
    exit();
}
if (isset($_SESSION['idFuncionario'])) {
    $_SESSION['msg'] = "Você já está logado!";
    header("Location: home-funcionario.php");
    exit();
}

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["nickname"]) && isset($_POST["senha"])) {
    $nome = $_POST["nickname"];
    $senha = $_POST["senha"];

    $conexao = mysqli_connect("localhost", "root", "", "duenos_pizza");
    if (!$conexao) {
        die("Erro na conexão: " . mysqli_connect_error());
    }


    $stmt = $conexao->prepare("SELECT * FROM clientes WHERE nickname = ?");
    $stmt->bind_param("s", $nome);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_assoc();
    $stmt->close();

    if ($usuario && password_verify($senha, $usuario["senha"])) {
        $_SESSION["nickname"] = $usuario["nickname"];
        $_SESSION["idCli"]   = $usuario["idCli"];
        $conexao->close();
        header("Location: index.php");
        exit();
    }


    $stmt = $conexao->prepare("SELECT * FROM funcionarios WHERE nickname = ?");
    $stmt->bind_param("s", $nome);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $func = $resultado->fetch_assoc();
    $stmt->close();
    $conexao->close();

    if ($func && password_verify($senha, $func["senha"])) {
        $_SESSION["nickname"] = $func["nickname"];
        $_SESSION["idFuncionario"]  = $func["idFuncionario"];
        $_SESSION["funcao"] = $func["funcao"];
        header("Location: home-funcionarios.php");
        exit();
    }


    $msg = "Usuário ou senha incorretos!";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Dueños de Pizza</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body 
        {
            background: #F5E9DA; 
            margin: 0; 
            padding: 0; 
            font-family: Arial, sans-serif; 
        }
        .navbar 
        { 
            background: transparent !important; 
            position: absolute; 
            width: 100%; 
            z-index: 10; 
            padding: 1.2rem 0; 
        }
        .navbar-brand img 
        { 
            height: 80px; 
        }
        .navbar .nav-link 
        { 
            color: #000 !important; 
            font-weight: 500; 
            font-size: 1.1rem; 
            padding: 0.8rem 1rem; 
            margin: 0 10px; 
        }
        .auth-buttons .btn-login, .auth-buttons .btn-logout 
        {
             
            background: #f00f22ff; 
            padding: 8px 15px; 
            border-radius: 5px; 
            color: #fff; 
            text-decoration: none; 
            transition: 0.3s; 
        }
        .auth-buttons .btn-login:hover, .auth-buttons .btn-logout:hover
        {
            background: #e21414ff; 
        }
        .login-container 
        { 
            min-height: 100vh; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            flex-direction: column; 
        }
        .login-box 
        { 
            background: #fff; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0px 5px 20px rgba(0,0,0,0.2);
            max-width: 400px; 
            width: 100%; 
        }
        .login-box h1 
        { 
            font-size: 1.8rem; 
            margin-bottom: 20px; 
            text-align: center; 
            color: #333;
        }
        .btn-primary
        { 
            background: #f80e21ff; 
            border: none; 
        }
        .btn-primary:hover 
        { 
            background: #e20d0dff;
        }
        .alert 
        {
            margin-bottom: 20px; 
            text-align: center; 
        }
        footer
        { 
            background: #222; 
            color: #fff; 
            text-align: center; 
            padding: 15px; 
            margin-top: 50px; 
        }
    </style>
</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="img/logo.png" alt="Dueños de Pizza">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="jogadores.php">Cardápio</a></li>
                    <li class="nav-item"><a class="nav-link" href="carrinho.php">Carrinho</a></li>
                    <li class="nav-item"><a class="nav-link" href="regras.php">Sobre</a></li>
                </ul>            
                <div class="auth-buttons">
                    <?php if(isset($_SESSION['idCli']) || isset($_SESSION['idFuncionario'])): ?>
                        <a href="logout.php" class="btn-logout">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="btn-login">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>

<br>

<div class="login-container">
    <div class="login-box">
        <h1>Login</h1>

        <?php if(isset($_SESSION['msg'])): ?>
            <div class="alert alert-info"><?= $_SESSION['msg']; ?></div>
            <?php unset($_SESSION['msg']); ?>
        <?php endif; ?>

        <?php if($msg != ""): ?>
            <div class="alert alert-danger"><?= $msg; ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="mb-3">
                <label for="nickname" class="form-label">Usuário:</label>
                <input type="text" id="nickname" name="nickname" required class="form-control">
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" id="senha" name="senha" required class="form-control">
            </div>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
        <p class="text-center mt-3">
            Não possui uma conta? <a href="cria-login.php" class="text-danger fw-bold">Crie uma aqui!</a>
        </p>
    </div>
</div>

<footer>
    <p>&copy; Dueños de Pizza 2025</p>
    <p>Todos os direitos reservados.</p>
</footer>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
