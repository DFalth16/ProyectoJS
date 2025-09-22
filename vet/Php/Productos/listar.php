<?php
session_start();
include '../db.php';
if (!isset($_SESSION['id_usuario']) || ($_SESSION['rol'] != 3 && $_SESSION['rol'] != 1)) {
    header('Location: ../inicio_sesion.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM productos");
$productos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Listar Productos</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="contenedor">
        <h1>Productos</h1>
        <table>
            <tr><th>ID</th><th>Nombre</th><th>Tipo</th><th>Precio</th><th>Stock</th><th>Acciones</th></tr>
            <?php foreach ($productos as $prod): ?>
                <tr>
                    <td><?php echo $prod['id']; ?></td>
                    <td><?php echo $prod['nombre']; ?></td>
                    <td><?php echo $prod['tipo']; ?></td>
                    <td><?php echo $prod['precio']; ?></td>
                    <td><?php echo $prod['stock']; ?></td>
                    <td>
                        <a href="editar.php?id=<?php echo $prod['id']; ?>">Editar</a>
                        <a href="eliminar.php?id=<?php echo $prod['id']; ?>">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>