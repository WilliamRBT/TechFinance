<?php
require_once '../includes/db.php';

$erro = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeCompleto = $_POST['nomeCompleto'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuario (nomeCompleto, email, senha) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nomeCompleto, $email, $senha);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        $erro = "Erro ao cadastrar usuário.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Cadastro</h1>
        <div class="card">
            <?php if ($erro): ?>
                <p style="color:red"><?= htmlspecialchars($erro) ?></p>
            <?php endif; ?>
            <form method="post">
                <div class="form-group">
                    <label>Nome Completo</label>
                    <input type="text" name="nomeCompleto" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Senha</label>
                    <input type="password" name="senha" required>
                </div>
                <button type="submit" class="btn">Cadastrar</button>
                <a href="login.php" class="btn">Já tem uma conta? Faça login</a>
            </form>
        </div>
    </div>
</body>
</html>