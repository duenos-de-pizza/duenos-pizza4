<?php
session_start();

if (!isset($_SESSION['idCli'])) {
    $_SESSION['msg'] = "Você precisa estar logado para finalizar o pedido!";
    header("Location: login.php");
    exit();
}

$conexao = mysqli_connect("localhost", "root", "", "duenos_pizza");
if (!$conexao) {
    die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
}

$idCli = $_SESSION['idCli'];

$sql = "UPDATE pedidos 
        SET status = 'Finalizado', dataPedido = NOW() 
        WHERE idCli = ? AND status = 'Em preparo'";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $idCli);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $mensagem = "✅ Pedido finalizado com sucesso!";
} else {
    $mensagem = "⚠️ Nenhum pedido para finalizar.";
}

$stmt->close();
$conexao->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Finalizar Pedido | Dueños de Pizza</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; background: #F5E9DA; text-align: center; padding: 50px; }
        h1 { color: #f80e21; margin-bottom: 20px; }
        p { font-size: 18px; color: #333; }
        a { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #f00f22; color: #fff; text-decoration: none; border-radius: 5px; transition: 0.3s; }
        a:hover { background: #d62828; }
    </style>
</head>
<body>

    <h1>Dueños de Pizza</h1>
    <p><?= $mensagem ?></p>
    <a href="cardapio.php">Voltar ao Cardápio</a>
    <a href="carrinho.php">Ver Carrinho</a>

</body>
</html>
