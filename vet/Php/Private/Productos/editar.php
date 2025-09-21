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

$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$id]);
$prod = $stmt->fetch();

if (!$prod) {
    echo "Producto no encontrado.";
    exit;
}

// Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    $stmt = $pdo->prepare("UPDATE productos SET nombre = ?, tipo = ?, precio = ?, stock = ? WHERE id = ?");
    $stmt->execute([$nombre, $tipo, $precio, $stock, $id]);
    header('Location: listar.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editar Producto</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="contenedor">
        <h1>Editar Producto</h1>
        <form method="POST">
            <input type="text" name="nombre" value="<?php echo $prod['nombre']; ?>" required>
            <select name="tipo" required>
                <option value="alimento" <?php if ($prod['tipo'] == 'alimento') echo 'selected'; ?>>Alimento</option>
                <!-- Opciones similares -->
            </select>
            <input type="number" step="0.01" name="precio" value="<?php echo $prod['precio']; ?>" required>
            <input type="number" name="stock" value="<?php echo $prod['stock']; ?>" required>
            <button type="submit">Actualizar</button>
        </form>
    </div>
</body>
</html>