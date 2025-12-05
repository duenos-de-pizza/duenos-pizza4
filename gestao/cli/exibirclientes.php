<?php
session_start();

if (!isset($_SESSION['nickname'])) {
    $_SESSION['msg'] = "Você precisa estar logado!";
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION["funcao"]) || 
    ($_SESSION["funcao"] !== "gerente" && $_SESSION["funcao"] !== "atendente")) {
    header("Location: func.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Todos os Clientes | Dueños de Pizza</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #F5E9DA;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 50px;
        }

        h1, h4 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .btn {
            margin: 10px;
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

        .cliente-card {
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0,0,0,0.2);
            margin: 10px 0;
            width: 300px;
        }
    </style>
</head>
<body>

    <h1>Clientes da Pizzaria</h1>
    <h4>Esses são todos os clientes cadastrados:</h4>

    <?php
    $conexao = mysqli_connect("localhost", "root", "", "duenos_pizza");
    if (!$conexao) {
        die("Erro na conexão: " . mysqli_connect_error());
    }

    $sql = "SELECT * FROM clientes";
    $resultado = mysqli_query($conexao, $sql);

    while ($linha = mysqli_fetch_assoc($resultado)) {
        echo '<div class="cliente-card">';
        echo "<b>ID:</b> " . $linha["idCli"] . "<br>";
        echo "<b>Nickname:</b> " . $linha["nickname"] . "<br>";
        echo "<b>Nome:</b> " . $linha["nome"] . "<br>";
        echo "<b>Email:</b> " . $linha["email"] . "<br>";
        echo "</div>";
    }

    mysqli_close($conexao);
    ?>

    <a href="cliente.php">
        <button class="btn">Voltar</button>
    </a>

</body>
</html>
