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
            color: #f80e21; 
            margin-bottom: 40px;
            text-align: center;
        }

        .btn {
            margin: 10px;
            padding: 12px 25px;
            background-color: #f00f22;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 1.1em;
            border-radius: 5px;
            transition: background-color 0.3s;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #e21414;
        }

        .btn-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        footer {
            position: fixed;
            bottom: 0;  
            width: 100%;
            background: #222;
            color: #fff;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body>

    <div class="btn-container">
        <a href="exibirprodutos.php" class="btn">Todos os Produtos</a>
        <a href="procurarprodutos.php" class="btn">Alterar Dados</a>
        <a href="adicionarproduto.php" class="btn">Adicionar Produto</a>
        <a href="../../home-funcionarios.php" class="btn">Voltar</a>
    </div>

    <footer>
        &copy; Dueños de Pizza 2025 - Todos os direitos reservados
    </footer>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
