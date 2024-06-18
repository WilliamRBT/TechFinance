<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

require_once '../includes/db.php';

$email = $_SESSION['email'];
$gastos = [];
$categorias = [];

$sql = "SELECT t.*, c.nome AS categoria_nome 
        FROM transacao t 
        LEFT JOIN categoria c ON t.idCategoria = c.id 
        WHERE t.tipo = 'gasto'
        ORDER BY t.data DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $gastos[] = $row;
    }
}

$sql = "SELECT * FROM categoria";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categorias[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];
    $data = $_POST['data'];
    $idCategoria = $_POST['idCategoria'];

    $sql = "INSERT INTO transacao (descricao, valor, data, tipo, idCategoria)
            VALUES (?, ?, ?, 'gasto', ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdis", $descricao, $valor, $data, $idCategoria);

    if ($stmt->execute()) {
        header("Location: gastos.php");
        exit();
    } else {
        echo "Erro ao adicionar gasto: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gastos</title>
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
        <h1>Gastos</h1>
        <div class="card">
            <canvas class="chart" data-type="line" data-title="Gastos ao longo do tempo" data-labels='<?= json_encode(array_column($gastos, 'data')) ?>' data-data='<?= json_encode(array_column($gastos, 'valor')) ?>'></canvas>
        </div>
        <div class="card">
            <h2>Adicionar Gasto</h2>
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
                <div class="form-group">
                    <label>Categoria</label>
                    <select name="idCategoria" required>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn">Adicionar</button>
            </form>
        </div>
        <div class="card">
            <h2>Lista de Gastos</h2>
            <table>
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Data</th>
                        <th>Categoria</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($gastos as $gasto): ?>
                        <tr>
                            <td><?= htmlspecialchars($gasto['descricao']) ?></td>
                            <td>R$ <?= number_format($gasto['valor'], 2, ',', '.') ?></td>
                            <td><?= htmlspecialchars($gasto['data']) ?></td>
                            <td><?= htmlspecialchars($gasto['categoria_nome']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>