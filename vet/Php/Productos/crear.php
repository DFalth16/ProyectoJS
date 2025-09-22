<?php
session_start();
include '../db.php';
if (!isset($_SESSION['id_usuario']) || ($_SESSION['rol'] != 3 && $_SESSION['rol'] != 1)) {
    header('Location: ../inicio_sesion.php');
    exit;
}

// Create
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    $stmt = $pdo->prepare("INSERT INTO productos (nombre, tipo, precio, stock) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre, $tipo, $precio, $stock]);
    header('Location: listar.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Crear Producto</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="contenedor">
        <h1>Agregar Producto</h1>
        <form method="POST">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <select name="tipo" required>
                <option value="alimento">Alimento</option>
                <option value="medicamento">Medicamento</option>
                <option value="accesorio">Accesorio</option>
            </select>
            <input type="number" step="0.01" name="precio" placeholder="Precio" required>
            <input type="number" name="stock" placeholder="Stock" required>
            <button type="submit">Agregar</button>
        </form>
    </div>
</body>
</html>