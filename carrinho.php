<?php
session_start();

if (!isset($_SESSION['idCli'])) {
    $_SESSION['msg'] = "Você precisa estar logado para ver o carrinho!";
    header("Location: login.php");
    exit();
}

$conexao = mysqli_connect("localhost", "root", "", "duenos_pizza");
if (!$conexao) {
    die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
}

if (isset($_GET['remove'])) {
    $idPedido = (int)$_GET['remove'];
    $stmt = $conexao->prepare("DELETE FROM pedidos WHERE idPed = ? AND idCli = ?");
    $stmt->bind_param("ii", $idPedido, $_SESSION['idCli']);
    $stmt->execute();
    $stmt->close();
}

$idCli = $_SESSION['idCli'];
$sql = "SELECT p.idPed, pr.nome, pr.preco, p.quantidade
        FROM pedidos p 
        JOIN prod pr ON p.idProd = pr.idProd 
        WHERE p.idCli = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $idCli);
$stmt->execute();
$resultado = $stmt->get_result();
$itens = $resultado->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Carrinho | Dueños de Pizza</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; background: #F5E9DA; padding: 20px; }
        .navbar { background: #222 !important; padding: 10px 20px; }
        .navbar-brand img { height: 60px; }
        .navbar .nav-link { color: #fff !important; font-weight: 500; margin: 0 10px; }
        .auth-buttons .btn-login, .auth-buttons .btn-logout {
            background: #e63946; color: #fff; padding: 8px 15px; border-radius: 5px; text-decoration: none; transition: 0.3s;
        }
        .auth-buttons .btn-login:hover, .auth-buttons .btn-logout:hover { background: #d62828; }
        h1 { text-align: center; color: #f80e21; margin-bottom: 30px; }
        table { margin: auto; width: 80%; background: #fff; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border-collapse: collapse; }
        th, td { padding: 10px; text-align: center; border-bottom: 1px solid #ddd; }
        .btn { padding: 5px 10px; background-color: #f00f22; color: white; border: none; border-radius: 5px; cursor: pointer; transition: 0.3s; }
        .btn:hover { background-color: #e21414; }
        .finalizar { margin-top: 20px; display: block; width: 200px; text-align: center; padding: 12px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px; margin-left: auto; margin-right: auto; }
        .finalizar:hover { background-color: #218838; }
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

    <?php if (count($itens) === 0): ?>
        <br><br>
        <p style="text-align:center; color:#555;">Seu carrinho está vazio.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Preço Unitário</th>
                    <th>Quantidade</th>
                    <th>Subtotal</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0;
                foreach ($itens as $item): 
                    $subtotal = $item['preco'] * $item['quantidade'];
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?= $item['nome'] ?></td>
                    <td>R$ <?= number_format($item['preco'],2,',','.') ?></td>
                    <td><?= $item['quantidade'] ?></td>
                    <td>R$ <?= number_format($subtotal,2,',','.') ?></td>
                    <td><a href="?remove=<?= $item['idPed'] ?>" class="btn">Remover</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total</th>
                    <th colspan="2">R$ <?= number_format($total,2,',','.') ?></th>
                </tr>
            </tfoot>
        </table>

        <a href="finalizar-pedido.php" class="finalizar">Finalizar Pedido</a>
    <?php endif; ?>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
