<?php
require_once '../includes/db.php';

session_start();
$erro = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuario WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['nomeCompleto'] = $usuario['nomeCompleto'];
            header("Location: index.php");
            exit();
        } else {
            $erro = "Senha incorreta.";
        }
    } else {
        $erro = "Usuário não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-user-lock"></i> Login</h1>
        <div class="card">
            <?php if ($erro): ?>
                <p class="error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($erro) ?></p>
            <?php endif; ?>
            <form method="post">
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="senha"><i class="fas fa-lock"></i> Senha</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                <button type="submit" class="btn"><i class="fas fa-sign-in-alt"></i> Login</button>
                <a href="cadastro.php" class="btn"><i class="fas fa-user-plus"></i> Cadastrar</a>
            </form>
        </div>
    </div>
</body>
</html>
