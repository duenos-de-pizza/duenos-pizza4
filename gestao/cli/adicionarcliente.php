<?php
session_start();

if (!isset($_SESSION['nickname'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION["funcao"]) || 
    ($_SESSION["funcao"] !== "gerente" && $_SESSION["funcao"] !== "atendente")) {
    header("Location: cliente.php"); 
    exit();
}

$erro = "";

if (isset($_POST["nickname"], $_POST["nome"], $_POST["senha"], $_POST["email"])) {
    $nickname = $_POST["nickname"];
    $nome = $_POST["nome"];
    $senha = $_POST["senha"];
    $email = $_POST["email"];

    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,16}$/", $senha)) {
        $erro = "A senha deve ter de 8 a 16 caracteres e conter letras maiúsculas, minúsculas e números (sem símbolos).";
    } else {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $conexao = mysqli_connect("localhost", "root", "", "duenos_pizza");

        $stmt = $conexao->prepare("INSERT INTO clientes (nickname, nome, senha, email) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nickname, $nome, $senha_hash, $email);

        $resultado = $stmt->execute();

        $stmt->close();
        $conexao->close();

        if ($resultado) {
            header("Location: exibirclientes.php");
            exit();
        } else {
            $erro = "Erro ao cadastrar cliente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Cliente | Dueños de Pizza</title>
    <link rel="stylesheet" href="externo.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            font-family: Arial, sans-serif;
            background-color: #F5E9DA;
        }

        h1 {
            font-size: 2em;
            color: #333;
            margin-bottom: 20px;
        }

        .btn {
            padding: 10px 20px;
            background-color: #f80e21ff;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 1em;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #e20d0dff;
        }

        .erro {
            color: red;
            margin-top: 10px;
            text-align: center;
        }

        input[type="text"], input[type="password"], input[type="email"] {
            width: 250px;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        form {
            text-align: center;
        }
    </style>
</head>
<body>
<section>
    <h1>Cadastrar Cliente</h1>
    <h5>Insira os dados do cliente que você quer cadastrar:</h5>
    <?php if ($erro != "") echo "<p class='erro'>$erro</p>"; ?>
    <form action="adicionarcliente.php" method="post">
        Nickname: <input type="text" name="nickname" required><br><br>
        Nome Completo: <input type="text" name="nome" required><br><br>
        Senha: <input type="password" name="senha" required><br><br>
        Email: <input type="email" name="email" required><br><br>
        <div style="display: flex; justify-content: center; gap: 10px;">
            <input type="submit" value="Inserir" class="btn">
            <a href="cliente.php" class="btn">Voltar</a>
        </div>
    </form>
</section>
</body>
</html>
