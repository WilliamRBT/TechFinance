<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

require_once '../includes/db.php';

$email = $_SESSION['email'];
$investimentos = [];

$sql = "SELECT * FROM investimento WHERE usuario_email = ? ORDER BY data DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $investimentos[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $valor_atual = $_POST['valor_atual'];
    $retorno = $_POST['retorno'];
    $data = $_POST['data'];

    $sql = "INSERT INTO investimento (nome, valor_atual, retorno, data, usuario_email)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sddss", $nome, $valor_atual, $retorno, $data, $email);

    if ($stmt->execute()) {
        header("Location: investimentos.php");
        exit();
    } else {
        echo "Erro ao adicionar investimento: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Investimentos</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../assets/js/main.js"></script>
</head>
<body>
 <header>
        <nav>
            <a href="index.php" class="btn home">Home</a>
        </nav>
    </header>
    <div class="container">
        <h1>Investimentos</h1>
        <div class="card">
            <canvas class="chart" data-type="bar" data-title="Investimentos ao longo do tempo" data-labels='<?= json_encode(array_column($investimentos, 'data')) ?>' data-data='<?= json_encode(array_column($investimentos, 'valor_atual')) ?>'></canvas>
        </div>
        <div class="card">
            <h2>Adicionar Investimento</h2>
            <form method="post">
                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" name="nome" required>
                </div>
                <div class="form-group">
                    <label>Valor Atual</label>
                    <input type="number" step="0.01" name="valor_atual" required>
                </div>
                <div class="form-group">
                    <label>Retorno (%)</label>
                    <input type="number" step="0.01" name="retorno" required>
                </div>
                <div class="form-group">
                    <label>Data</label>
                    <input type="date" name="data" required>
                </div>
                <button type="submit" class="btn">Adicionar</button>
            </form>
        </div>
        <div class="card">
            <h2>Lista de Investimentos</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Valor Atual</th>
                        <th>Retorno (%)</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($investimentos as $investimento): ?>
                        <tr>
                            <td><?= htmlspecialchars($investimento['nome']) ?></td>
                            <td>R$ <?= number_format($investimento['valor_atual'], 2, ',', '.') ?></td>
                            <td><?= number_format($investimento['retorno'], 2, ',', '.') ?>%</td>
                            <td><?= htmlspecialchars($investimento['data']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>