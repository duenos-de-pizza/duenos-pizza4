<?php
session_start();

if (!isset($_SESSION['idFuncionario'])) {
    $_SESSION['msg'] = "Você precisa estar logado como funcionário para acessar esta página!";
    header("Location: login.php");
    exit();
}

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST["nome"];
    $preco = $_POST["preco"];
    $qtde = $_POST["qtde"];


    if ($nome === "" || !is_numeric($preco)) {
        $erro = "Preencha todos os campos corretamente.";
    } else {
        $conexao = mysqli_connect("localhost", "root", "", "duenos_pizza");
        if (!$conexao) {
            $erro = "Erro na conexão com o banco de dados.";
        } else {
            $stmt = $conexao->prepare("INSERT INTO prod (nome, preco, qtde) VALUES (?, ?, ?)");
            $stmt->bind_param("sdi", $nome, $preco, $qtde);
            $resultado = $stmt->execute();

            $stmt->close();
            $conexao->close();

            if ($resultado) {
                header("Location: exibirprodutos.php");
                exit();
            } else {
                $erro = "Erro ao cadastrar produto.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Produto | Dueños de Pizza</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #F5E9DA;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        .cadastro-box {
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

        input[type="text"], input[type="number"] {
            width: 80%;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
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

        .erro {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="cadastro-box">
        <h1>Adicionar Produto</h1>

        <?php if ($erro != "") echo "<p class='erro'>$erro</p>"; ?>

        <form method="post" action="adicionarproduto.php">
            <input type="text" name="nome" placeholder="Nome do produto" required><br>
            <input type="number" step="0.01" name="preco" placeholder="Preço (R$)" required><br>
            <input type="number" name="qtde" placeholder="Quantidade"><br>
            <button type="submit" class="btn">Adicionar</button>
            <a href="produtos.php" class="btn">Voltar</a>
        </form>
    </div>
</body>
</html>
