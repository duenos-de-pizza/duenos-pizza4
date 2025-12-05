<?php
session_start();

if (!isset($_SESSION['idCli'])) {
    $_SESSION['msg'] = "Você precisa estar logado para fazer pedidos!";
    header("Location: login.php");
    exit();
}

$mensagem = "";

$conexao = mysqli_connect("localhost", "root", "", "duenos_pizza");
if (!$conexao) {
    die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
}

if (isset($_GET['add'])) {
    $idCli = $_SESSION['idCli'];
    $idProd = (int)$_GET['add'];
    $quantidade = 1; 


    if (isset($_GET['qtde'])) {
        $quantidade = max(1, (int)$_GET['qtde']); 
    }

    $stmt = $conexao->prepare("INSERT INTO pedidos (idCli, idProd, quantidade) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $idCli, $idProd, $quantidade);
    $stmt->execute();
    $stmt->close();

    $mensagem = "Item adicionado ao carrinho!";
}

$produtos = [
    1 => ["nome" => "Mussarela", "preco" => 32, "tipo" => "pizza"],
    2 => ["nome" => "Calabresa", "preco" => 34, "tipo" => "pizza"],
    3 => ["nome" => "Portuguesa", "preco" => 36, "tipo" => "pizza"],
    10 => ["nome" => "Refrigerante Lata", "preco" => 6, "tipo" => "bebida"],
    11 => ["nome" => "Refrigerante 2L", "preco" => 12, "tipo" => "bebida"],
    12 => ["nome" => "Água Mineral", "preco" => 4, "tipo" => "bebida"]
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cardápio | Dueños de Pizza</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; background: #F5E9DA; padding: 20px; }

        .navbar { background: #000 !important; padding: 10px 20px; }
        .navbar-brand img { height: 60px; }
        .navbar .nav-link { color: #ffffffff !important; font-weight: 500; margin: 0 10px; }
        .auth-buttons .btn-login, .auth-buttons .btn-logout {
            background: #e63946; color: #fff; padding: 8px 15px; border-radius: 5px; text-decoration: none; transition: 0.3s;
        }
        .auth-buttons .btn-login:hover, .auth-buttons .btn-logout:hover { background: #d62828; }

        h1 { text-align: center; color: #000000ff; margin-bottom: 30px; }
        .mensagem { text-align: center; color: green; font-weight: bold; margin-bottom: 20px; }
        .item { background: #fff; border-radius: 8px; padding: 15px; margin: 10px auto; max-width: 600px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; }
        .item p { margin: 0; font-size: 1em; }
        .btn { padding: 8px 15px; background-color: #f00f22; color: white; border: none; border-radius: 5px; cursor: pointer; transition: 0.3s; }
        .btn:hover { background-color: #e21414; }
        input[type="number"] { width: 60px; margin-left: 10px; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="index.php">
            <img src="img/logo.png" alt="Dueños de Pizza">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="cardapio.php">Cardápio</a></li>
                <li class="nav-item"><a class="nav-link" href="carrinho.php">Carrinho</a></li>
                <li class="nav-item"><a class="nav-link" href="regras.php">Sobre</a></li>
            </ul>
            <div class="auth-buttons">
                <?php if(isset($_SESSION['idCli']) || isset($_SESSION['idFuncionario'])): ?>
                    <a href="logout.php" class="btn-logout">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn-login">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <br>
    <br>

    <h1>Cardápio Dueños de Pizza</h1>

    <?php if ($mensagem != "") echo "<p class='mensagem'>$mensagem</p>"; ?>

    <?php foreach ($produtos as $id => $produto): ?>
        <div class="item">
            <p><strong><?= $produto['nome'] ?></strong> – R$ <?= number_format($produto['preco'],2,',','.') ?></p>
            <?php if ($produto['tipo'] === 'pizza'): ?>
                <a href="?add=<?= $id ?>" class="btn">Adicionar ao carrinho</a>
            <?php else: ?>
                <form method="get" style="display:inline;">
                    <input type="hidden" name="add" value="<?= $id ?>">
                    <input type="number" name="qtde" value="1" min="1">
                    <button type="submit" class="btn">Adicionar ao carrinho</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
