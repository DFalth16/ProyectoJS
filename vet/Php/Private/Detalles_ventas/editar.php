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

$stmt = $pdo->prepare("SELECT * FROM detalles_ventas WHERE id = ?");
$stmt->execute([$id]);
$det = $stmt->fetch();

if (!$det) {
    echo "Detalle no encontrado.";
    exit;
}

// Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo_item = $_POST['tipo_item'];
    $id_item = $_POST['id_item'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];

    $stmt = $pdo->prepare("UPDATE detalles_ventas SET tipo_item = ?, id_item = ?, cantidad = ?, precio = ? WHERE id = ?");
    $stmt->execute([$tipo_item, $id_item, $cantidad, $precio, $id]);
    header('Location: listar.php?id_venta=' . $det['id_venta']);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editar Detalle de Venta</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="contenedor">
        <h1>Editar Detalle</h1>
        <form method="POST">
            <select name="tipo_item" required>
                <option value="producto" <?php if ($det['tipo_item'] == 'producto') echo 'selected'; ?>>Producto</option>
                <option value="servicio" <?php if ($det['tipo_item'] == 'servicio') echo 'selected'; ?>>Servicio</option>
            </select>
            <input type="number" name="id_item" value="<?php echo $det['id_item']; ?>" required>
            <input type="number" name="cantidad" value="<?php echo $det['cantidad']; ?>" required>
            <input type="number" step="0.01" name="precio" value="<?php echo $det['precio']; ?>" required>
            <button type="submit">Actualizar</button>
        </form>
    </div>
</body>
</html>