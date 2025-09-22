<?php
session_start();
include '../db.php';
if (!isset($_SESSION['id_usuario']) || ($_SESSION['rol'] != 3 && $_SESSION['rol'] != 1)) {
    header('Location: ../inicio_sesion.php');
    exit;
}

// Get clientes
$stmt = $pdo->query("SELECT * FROM usuarios WHERE rol = 4");
$clientes = $stmt->fetchAll();

// Create venta (luego agregar detalles)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fecha = date('Y-m-d H:i:s');
    $id_cliente = $_POST['id_cliente'];
    $total = 0; // Se actualizará con detalles

    $stmt = $pdo->prepare("INSERT INTO ventas (fecha, id_cliente, total) VALUES (?, ?, ?)");
    $stmt->execute([$fecha, $id_cliente, $total]);
    $id_venta = $pdo->lastInsertId();
    header('Location: ../Detalles_ventas/crear.php?id_venta=' . $id_venta); // Redirige a agregar detalles
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Crear Venta</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="contenedor">
        <h1>Registrar Venta</h1>
        <form method="POST">
            <select name="id_cliente" required>
                <?php foreach ($clientes as $cli): ?>
                    <option value="<?php echo $cli['id']; ?>"><?php echo $cli['nombre']; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Crear Venta (agregar detalles después)</button>
        </form>
    </div>
</body>
</html>