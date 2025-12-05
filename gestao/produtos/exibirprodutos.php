<?php
    session_start();

    if (!isset($_SESSION['idFuncionario'])) {
        $_SESSION['msg'] = "Você precisa estar logado como funcionário para acessar esta página!";
        header("Location: login.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Produtos | Dueños de Pizza</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #F5E9DA;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #f80e21;
            margin-bottom: 30px;
        }

        .produto {
            background: #fff;
            border-radius: 8px;
            padding: 15px;
            margin: 10px auto;
            max-width: 600px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .produto p {
            margin: 5px 0;
            font-size: 1em;
            color: #333;
        }

        .btn {
            display: block;
            width: 200px;
            margin: 30px auto;
            padding: 12px;
            background-color: #f00f22;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }

        .btn:hover {
            background-color: #e21414;
        }
    </style>
</head>
<body>

    <h1>Lista de Produtos</h1>

    <?php
        $conexao = mysqli_connect("localhost", "root", "", "duenos_pizza");

        if (!$conexao) {
            die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
        }

        $sql = "SELECT idProd, nome, preco, qtde FROM prod";
        $resultado = mysqli_query($conexao, $sql);

        if (mysqli_num_rows($resultado) > 0) {
            while($linha = mysqli_fetch_assoc($resultado)) {
                echo "<div class='produto'>";
                echo "<p><strong>ID:</strong> " . $linha["idProd"] . "</p>";
                echo "<p><strong>Nome:</strong> " . $linha["nome"] . "</p>";
                echo "<p><strong>Preço:</strong> R$ " . number_format($linha["preco"], 2, ',', '.') . "</p>";
                echo "<p><strong>Quantidade:</strong> " . $linha["qtde"] . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p style='text-align:center; color:#555;'>Nenhum produto cadastrado.</p>";
        }

        mysqli_close($conexao);
    ?>

    <a href="produtos.php" class="btn">Voltar</a>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
