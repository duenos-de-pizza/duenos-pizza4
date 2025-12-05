<?php
session_start();

if (!isset($_SESSION['idFuncionario'])) {
    $_SESSION['msg'] = "Você precisa estar logado como funcionário para acessar esta página!";
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "ID do produto não informado.";
    exit();
}

$id = $_GET['id'];
$conexao = mysqli_connect("localhost", "root", "", "duenos_pizza");
if (!$conexao) {
    die("Erro na conexão: " . mysqli_connect_error());
}

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['novo_nome'];
    $preco = $_POST['novo_preco'];
    $qtde = $_POST['nova_qtde'];

    $stmt = $conexao->prepare("UPDATE prod SET nome = ?, preco = ?, qtde = ? WHERE idProd = ?");
    $stmt->bind_param("sdii", $nome, $preco, $qtde, $id);

    if ($stmt->execute()) {
        header("Location: exibirprodutos.php");
        exit();
    } else {
        $erro = "Erro ao atualizar o produto.";
    }

    $stmt->close();
}

$stmt = $conexao->prepare("SELECT nome, preco, qtde FROM prod WHERE idProd = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($nome, $preco, $qtde);
if (!$stmt->fetch()) {
    echo "Produto não encontrado.";
    exit();
}
$stmt->close();
$conexao->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Alterar Produto | Dueños de Pizza</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #F5E9DA;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .alterar-box {
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
    <div class="alterar-box">
        <h1>Alterar Produto</h1>
        <?php if ($erro != "") echo "<p class='erro'>$erro</p>"; ?>
        <form method="post" action="alterardados.php?id=<?= $id ?>">
            <input type="text" name="novo_nome" value="<?= $nome ?>" placeholder="Nome do Produto" required><br>
            <input type="number" step="0.01" name="novo_preco" value="<?= $preco ?>" placeholder="Preço" required><br>
            <input type="number" name="nova_qtde" value="<?= $qtde ?>" placeholder="Quantidade"><br>
            <button type="submit" class="btn">Alterar</button>
            <a href="exibirprodutos.php" class="btn">Voltar</a>
        </form>
    </div>
</body>
</html>
