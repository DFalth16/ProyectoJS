<?php
include 'db.php';
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 1) {
    header('Location: index.php');
    exit;
}

// Ejemplo de reporte de ventas (agrega lógica para otros reportes)
$ventas = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_cliente = $_POST['id_cliente'] ?? '';
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_fin = $_POST['fecha_fin'] ?? '';

    $query = "SELECT * FROM ventas WHERE 1=1";
    $params = [];
    if ($id_cliente) {
        $query .= " AND id_cliente = ?";
        $params[] = $id_cliente;
    }
    if ($fecha_inicio) {
        $query .= " AND fecha >= ?";
        $params[] = $fecha_inicio;
    }
    if ($fecha_fin) {
        $query .= " AND fecha <= ?";
        $params[] = $fecha_fin;
    }
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $ventas = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reportes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Reportes</h1>
        <h2>Reporte de Ventas</h2>
        <form method="POST">
            <input type="text" name="id_cliente" placeholder="ID Cliente">
            <input type="date" name="fecha_inicio" placeholder="Fecha Inicio">
            <input type="date" name="fecha_fin" placeholder="Fecha Fin">
            <button type="submit">Generar</button>
        </form>
        <table>
            <tr><th>ID</th><th>Fecha</th><th>Cliente</th><th>Total</th></tr>
            <?php foreach ($ventas as $venta): ?>
                <tr>
                    <td><?php echo $venta['id']; ?></td>
                    <td><?php echo $venta['fecha']; ?></td>
                    <td><?php echo $venta['id_cliente']; ?></td>
                    <td><?php echo $venta['total']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <!-- Agrega secciones para otros reportes -->
    </div>
</body>
</html>