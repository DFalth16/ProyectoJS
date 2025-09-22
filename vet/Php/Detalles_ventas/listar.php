<?php
session_start();
include '../db.php';
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../inicio_sesion.php');
    exit;
}

if (!isset($_GET['id_venta'])) {
    echo "ID de venta requerido.";
    exit;
}
$id_venta = $_GET['id_venta'];

$stmt = $pdo->prepare("SELECT * FROM detalles_ventas WHERE id_venta = ?");
$stmt->execute([$id_venta]);
$detalles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Listar Detalles de Venta</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="contenedor">
        <h1>Detalles de Venta ID: <?php echo $id_venta; ?></h1>
        <table>
            <tr><th>ID</th><th>Tipo</th><th>Item ID</th><th>Cantidad</th><th>Precio</th><th>Acciones</th></tr>
            <?php foreach ($detalles as $det): ?>
                <tr>
                    <td><?php echo $det['id']; ?></td>
                    <td><?php echo $det['tipo_item']; ?></td>
                    <td><?php echo $det['id_item']; ?></td>
                    <td><?php echo $det['cantidad']; ?></td>
                    <td><?php echo $det['precio']; ?></td>
                    <td>
                        <a href="editar.php?id=<?php echo $det['id']; ?>">Editar</a>
                        <a href="eliminar.php?id=<?php echo $det['id']; ?>">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>