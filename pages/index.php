<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../assets/css/styles.css"> 
</head>
<body>
    <header>
        <nav>
            <a href="index.php" class="btn home">
                <i class="fas fa-home"></i> Home
            </a>
        </nav>
    </header>
    <main class="container">
        <?php
        session_start();
        if (!isset($_SESSION['email'])) {
            header("Location: login.php");
            exit();
        }

        $nomeCompleto = $_SESSION['nomeCompleto'];
        ?>

        <h1 class="titulo-principal">Bem-vindo, <?= htmlspecialchars($nomeCompleto) ?></h1>
        <div class="card">
            <h2>Recursos</h2>
            <ul>
                <li><a href="gastos.php" class="btn">
                    <i class="fas fa-money-bill-wave"></i> Gastos
                </a></li>
                <li><a href="economias.php" class="btn">
                    <i class="fas fa-piggy-bank"></i> Economias
                </a></li>
                <li><a href="investimentos.php" class="btn">
                    <i class="fas fa-chart-line"></i> Investimentos
                </a></li>
                <li><a href="logout.php" class="btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a></li>
            </ul>
        </div>
        <hr>
        <section class="info-section">
            <p class="slogan">Simplificando suas finanças.</p>
            <ul class="links-info">
                <li><a href="#">Termos de Serviço</a></li>
                <li><a href="#">Política de Privacidade</a></li>
                <li><a href="#">Fale Conosco: teste@teste.com</a></li>
            </ul>
        </section>
    </main>
    <footer>
        <p>© 2024 TechFinance</p>
    </footer>
</body>

</html>