<?php
session_start();

if (!isset($_SESSION['nickname'])) {
    $_SESSION['msg'] = "Você precisa estar logado!";
    header("Location: login.php");
    exit();
}

$id = $_SESSION["idFuncionario"]; 
$erro = "";

$conexao = mysqli_connect("localhost", "root", "", "duenos_pizza");
if (!$conexao) {
    die("Erro na conexão: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $senha = $_POST["novasenha"];
    
    if ($senha !== "") {
        if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,16}$/", $senha)) {
            $erro = "A senha deve ter de 8 a 16 caracteres e conter letras maiúsculas, minúsculas e números (sem símbolos).";
        } else {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            $stmt = $conexao->prepare("UPDATE funcionarios SET senha = ? WHERE idFuncionario = ?");
            $stmt->bind_param("si", $senha_hash, $id);
            $stmt->execute();
            $stmt->close();

            $erro = "Senha alterada com sucesso!";
        }
    } else {
        $erro = "Digite uma nova senha!";
    }
}

mysqli_close($conexao);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Alterar Senha | Dueños de Pizza</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #F5E9DA;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .alterar-senha-box {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 5px 20px rgba(0,0,0,0.2);
            text-align: center;
            width: 350px;
        }

        h1 {
            color: #333;
            margin-bottom: 15px;
        }

        h5 {
            color: #555;
            margin-bottom: 20px;
        }

        .btn {
            padding: 10px 20px;
            margin: 5px;
            background-color: #f80e21ff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            background-color: #e20d0dff;
        }

        input[type="password"] {
            width: 80%;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .erro {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="alterar-senha-box">
        <h1>Alterar Senha</h1>
        <h5>Digite a nova senha que você quer ter:</h5>

        <?php if ($erro != "") echo "<p class='erro'>$erro</p>"; ?>

        <form method="post" action="">
            <input type="password" name="novasenha" placeholder="Nova senha"><br>
            <button type="submit" class="btn">Alterar</button>
            <a href="func.php" class="btn">Voltar</a>
        </form>
    </div>
</body>
</html>
