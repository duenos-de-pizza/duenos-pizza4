<?php
session_start();

if (!isset($_SESSION['nickname'])) {
    $_SESSION['msg'] = "Você precisa estar logado!";
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION["funcao"]) || $_SESSION["funcao"] !== "gerente") {
    $_SESSION['msg'] = "Acesso restrito ao gerente!";
    header("Location: cliente.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Procurar Cliente | Dueños de Pizza</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
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

        .procurar-box {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 5px 20px rgba(0,0,0,0.2);
            text-align: center;
        }

        h1, h5 {
            color: #333;
            margin-bottom: 15px;
        }

        input[type="number"] {
            padding: 8px;
            width: 200px;
            border-radius: 5px;
            border: 1px solid #ccc;
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

        .erro {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="procurar-box">
        <h1>Alterar dados dos clientes</h1>
        <h5>Coloque o ID do cliente que você quer alterar:</h5>

        <form action="procuracliente.php" method="post">
            <input type="number" name="id" required placeholder="ID do cliente"><br>
            <button type="submit" class="btn">Enviar</button>
            <a href="../menu.php" class="btn">Voltar</a>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
            $id = $_POST["id"];
            $conexao = mysqli_connect("localhost", "root", "", "duenos_pizza");

            if (!$conexao) {
                echo "<p class='erro'>Erro na conexão com o banco de dados.</p>";
            } else {
                $stmt = $conexao->prepare("SELECT * FROM clientes WHERE idCli = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $resultado = $stmt->get_result();

                if ($linha = $resultado->fetch_assoc()) {
                    $stmt->close();
                    $conexao->close();
                    header("Location: alterardados.php?id=" . urlencode($linha["idCli"]));
                    exit();
                } else {
                    echo "<p class='erro'>Cliente não encontrado.</p>";
                }

                $stmt->close();
                $conexao->close();
            }
        }
        ?>
    </div>
</body>
</html>
