<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

require_once '../includes/db.php';

$email = $_SESSION['email'];
$economias = [];

$sql = "SELECT * FROM economia WHERE usuario_email = ? ORDER BY data DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $economias[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];
    $data = $_POST['data'];

    $sql = "INSERT INTO economia (descricao, valor, data, usuario_email) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siss", $descricao, $valor, $data, $email);

    if ($stmt->execute()) {
        header("Location: economias.php");
        exit();
    } else {
        echo "Erro ao adicionar economia: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Economias</title>
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
        <h1>Economias</h1>
        <div class="card">
            <canvas class="chart" data-type="line" data-title="Economias ao longo do tempo" data-labels='<?= json_encode(array_column($economias, 'data')) ?>' data-data='<?= json_encode(array_column($economias, 'valor')) ?>'></canvas>
        </div>
        <div class="card">
            <h2>Adicionar Economia</h2>
            <form method="post">
                <div class="form-group">
                    <label>Descrição</label>
                    <input type="text" name="descricao" required>
                </div>
                <div class="form-group">
                    <label>Valor</label>
                    <input type="number" step="0.01" name="valor" required>
                </div>
                <div class="form-group">
                    <label>Data</label>
                    <input type="date" name="data" required>
                </div>
                <button type="submit" class="btn">Adicionar</button>
            </form>
        </div>
        <div class="card">
            <h2>Lista de Economias</h2>
            <table>
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($economias as $economia): ?>
                        <tr>
                            <td><?= htmlspecialchars($economia['descricao']) ?></td>
                            <td>R$ <?= number_format($economia['valor'], 2, ',', '.') ?></td>
                            <td><?= htmlspecialchars($economia['data']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>