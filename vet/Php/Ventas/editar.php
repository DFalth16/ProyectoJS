<?php
session_start();
include '../db.php';
if (!isset($_SESSION['id_usuario']) || ($_SESSION['rol'] != 3 && $_SESSION['rol'] != 1)) {
    header('Location: ../inicio_sesion.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: listar.php');
    exit;
}
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM ventas WHERE id = ?");
$stmt->execute([$id]);
$venta = $stmt->fetch();

if (!$venta) {
    echo "Venta no encontrada.";
    exit;
}

// Get clientes
$stmt = $pdo->query("SELECT * FROM usuarios WHERE rol = 4");
$clientes = $stmt->fetchAll();

// Update (total se recalcula con detalles)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_cliente = $_POST['id_cliente'];

    $stmt = $pdo->prepare("UPDATE ventas SET id_cliente = ? WHERE id = ?");
    $stmt->execute([$id_cliente, $id]);
    header('Location: listar.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editar Venta</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="contenedor">
        <h1>Editar Venta</h1>
        <form method="POST">
            <select name="id_cliente" required>
                <?php foreach ($clientes as $cli): ?>
                    <option value="<?php echo $cli['id']; ?>" <?php if ($cli['id'] == $venta['id_cliente']) echo 'selected'; ?>><?php echo $cli['nombre']; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Actualizar</button>
        </form>
        <a href="../Detalles_ventas/listar.php?id_venta=<?php echo $id; ?>">Gestionar Detalles</a>
    </div>
</body>
</html>