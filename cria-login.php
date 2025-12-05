<?php
    session_start();

    if (isset($_SESSION['cliente_id'])) {
        $_SESSION['msg'] = "Você já está logado!";
        header("Location: index.php");
        exit();
    }

    $erro = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST["nickname"], $_POST["senha"], $_POST["nome"], $_POST["email"])) {
            $nickname = trim($_POST["nickname"]);
            $senha = $_POST["senha"];
            $nome = trim($_POST["nome"]);
            $email = trim($_POST["email"]);

            if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,16}$/", $senha)) {
                $erro = "A senha deve ter de 8 a 16 caracteres e conter letras maiúsculas, minúsculas e números (sem símbolos).";
            } else {
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                $conexao = mysqli_connect("localhost", "root", "", "duenos_pizza");

                if ($conexao->connect_error) {
                    die("Falha na conexão: " . $conexao->connect_error);
                }

                $stmt = $conexao->prepare("INSERT INTO clientes (nickname, senha, nome, email) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $nickname, $senhaHash, $nome, $email);

                $resultado = $stmt->execute();

                if ($resultado) {
                    $_SESSION['msg'] = "Conta criada com sucesso! Agora faça login.";
                    header("Location: login.php");
                    exit();
                } else {
                    $erro = "Houve um erro ao criar sua conta. Tente novamente.";
                }

                $stmt->close();
                $conexao->close();
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - Dueños de Pizza</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body {
            background: #F5E9DA;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background: transparent !important;
            position: absolute;
            width: 100%;
            z-index: 10;
            padding-top: 1.2rem;
            padding-bottom: 1.2rem;
        }

        .navbar-brand img {
            height: 80px;
        }

        .navbar .nav-link {
            color: #000000ff !important; 
            font-weight: 500;
            font-size: 1.1rem;
            padding: 0.8rem 1rem;
            margin: 0 10px;
            padding-bottom: 5px;
        }

        .auth-buttons .btn-login, 
        .auth-buttons .btn-logout {
            background: #f00f22ff;
            padding: 8px 15px;
            border-radius: 5px;
            color: #fff;
            text-decoration: none;
            transition: 0.3s;
        }

        .auth-buttons .btn-login:hover, 
        .auth-buttons .btn-logout:hover {
            background: #f00f22ff;
        }

        section {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            width: 400px;
            margin: 150px auto 50px auto; 
            text-align: center;
        }

        h1 {
            font-size: 2em;
            color: #f00f22ff;
            margin-bottom: 10px;
        }

        h5 {
            color: #333;
            margin-bottom: 20px;
        }

        input[type="text"], 
        input[type="password"], 
        input[type="email"] {
            width: 90%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .btn {
            padding: 8px 15px;
            background-color: #f00f22ff;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 1em;
            border-radius: 8px;
            transition: background-color 0.3s;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #d62828;
        }

        .erro {
            color: red;
            margin: 10px 0;
        }

        .extra {
            margin-top: 15px;
            font-size: 0.9em;
        }

        .extra a {
            color: #e63946;
            text-decoration: none;
        }

        .extra a:hover {
            text-decoration: underline;
        }

        footer {
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
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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
                        <?php if(isset($_SESSION['cliente_id'])): ?>
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

    <section>
        <h1>Criar Conta</h1>
        <h5>Insira seus dados para criar seu login:</h5>
        <?php if ($erro != "") echo "<p class='erro'>$erro</p>"; ?>

        <form action="cria-login.php" method="post">
            <input type="text" name="nickname" placeholder="Nickname" required><br>
            <input type="password" name="senha" placeholder="Senha" required><br>
            <input type="text" name="nome" placeholder="Nome Completo" required><br>
            <input type="email" name="email" placeholder="Email" required><br>

            <div style="margin-top:15px; display:flex; justify-content:center; gap:10px;">
                <input type="submit" value="Criar Conta" class="btn">
                <a href="login.php" class="btn">Voltar</a>
            </div>
        </form>

        <div class="extra">
            Já possui uma conta? <a href="login.php">Faça login aqui!</a>
        </div>
    </section>

    <footer>
        <p>&copy; Dueños de Pizza 2025</p>
        <p>Todos os direitos reservados.</p>
    </footer>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
