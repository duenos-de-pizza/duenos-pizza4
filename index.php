<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dueños de Pizza</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #F5E9DA;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background: transparent !important;
            position: absolute;
            width: 100%;
            z-index: 10; 
            padding-top: 1.2rem;
            padding-bottom: 1.2rem;
        }

        .navbar-brand img {
        height: 80px;
        }

        .navbar .nav-link {
            color: #fff !important; 
            font-weight: 500;
            font-size: 1.1rem;
            padding: 0.8rem 1rem;
            margin: 0 10px;
            padding-bottom: 5px;
        }

        .auth-buttons .btn-login, 
        .auth-buttons .btn-logout {
            background: #e63946;
            padding: 8px 15px;
            border-radius: 5px;
            color: #fff;
            text-decoration: none;
            transition: 0.3s;
        }

        .auth-buttons .btn-login:hover, 
        .auth-buttons .btn-logout:hover {
            background: #d62828;
        }

        .hero {
            height: 100vh; 
            background: url("img/banner.jpeg") no-repeat center center;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            text-align: center;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: bold;
            text-shadow: 2px 2px 5px rgba(0,0,0,0.7);
        }
    </style>
</head>
<body>
   
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">

            <a class="navbar-brand" href="index.php">
                    <img src="img/logo.png" alt="Dueños de Pizza" height="60">
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
            </div>
        </nav>
    </header>

    <section class="hero">
        <h1>Bem-vindo à Dueños de Pizza 🍕</h1>
    </section>
    
    <br>
    <br>

    <footer class="bg-dark text-white text-center py-3 container-fluid">
        <p>&copy; Dueños de Pizza 2025</p>
        <p>Todos os direitos reservados.</p>
    </footer>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
