<?php
session_start();

if (!isset($_SESSION['nickname']) || $_SESSION["funcao"] !== "gerente") {
    $_SESSION['msg'] = "Acesso restrito ao gerente!";
    header("Location: ../../index.php");
    exit();
}

$conexao = mysqli_connect("localhost", "root", "", "duenos_pizza");
if (!$conexao) {
    die("Erro na conexão: " . mysqli_connect_error());
}

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["novoid"];
    $nome = $_POST["novonome"];
    $nickname = $_POST["novonickname"];
    $email = $_POST["novoemail"];
    $senha = $_POST["novasenha"];

    $sem_erro = true;

    if ($senha !== "") {
        if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,16}$/", $senha)) {
            $erro = "A senha deve ter de 8 a 16 caracteres, com letras maiúsculas, minúsculas e números (sem símbolos).";
            $sem_erro = false;
        } else {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        }
    }

    if ($sem_erro) {
        if ($senha !== "") {
            $stmt = $conexao->prepare("UPDATE clientes SET nickname = ?, senha = ?, nome = ?, email = ? WHERE idCli = ?");
            $stmt->bind_param("ssssi", $nickname, $senha_hash, $nome, $email, $id);
        } else {
            $stmt = $conexao->prepare("UPDATE clientes SET nickname = ?, nome = ?, email = ? WHERE idCli = ?");
            $stmt->bind_param("sssi", $nickname, $nome, $email, $id);
        }

        if ($stmt->execute()) {
            $stmt->close();
            $conexao->close();
            header("Location: exibirclientes.php");
            exit();
        } else {
            $erro = "Erro ao atualizar os dados.";
        }
    }
}

if (!isset($_GET["id"])) {
    echo "ID não informado.";
    exit();
}

$id = $_GET["id"];

$stmt = $conexao->prepare("SELECT nome, nickname, email FROM clientes WHERE idCli = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($nome, $nickname, $email);
if (!$stmt->fetch()) {
    echo "Cliente não encontrado.";
    exit();
}
$stmt->close();
$conexao->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Alterar Cliente | Dueños de Pizza</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #F5E9DA;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .alterar-dados-box {
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

        input[type="text"], input[type="password"], input[type="email"] {
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
    <div class="alterar-dados-box">
        <h1>Alterar Cliente</h1>

        <?php if ($erro != "") echo "<p class='erro'>$erro</p>"; ?>

        <form method="post" action="alterardados.php?id=<?= $id ?>">
            <input type="hidden" name="novoid" value="<?= $id ?>">
            <input type="text" name="novonickname" value="<?= $nickname ?>" placeholder="Nickname"><br>
            <input type="text" name="novonome" value="<?= $nome ?>" placeholder="Nome"><br>
            <input type="email" name="novoemail" value="<?= $email ?>" placeholder="Email"><br>
            <input type="password" name="novasenha" placeholder="Nova Senha (opcional)"><br>
            <button type="submit" class="btn">Alterar</button>
            <a href="procuracliente.php" class="btn">Voltar</a>
        </form>
    </div>
</body>
</html>
