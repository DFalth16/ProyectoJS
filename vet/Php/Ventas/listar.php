<?php
session_start();
include '../db.php';
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../inicio_sesion.php');
    exit;
}
$rol = $_SESSION['rol'];
$id_usuario = $_SESSION['id_usuario'];

if ($rol == 4) {
    $stmt = $pdo->prepare("SELECT v.*, u.nombre as nombre_cliente FROM ventas v JOIN usuarios u ON v.id_cliente = u.id WHERE v.id_cliente = ?");
    $stmt->execute([$id_usuario]);
} else {
    $stmt = $pdo->query("SELECT v.*, u.nombre as nombre_cliente FROM ventas v JOIN usuarios u ON v.id_cliente = u.id");
}
$ventas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Listar Ventas</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="contenedor">
        <h1>Ventas</h1>
        <table>
            <tr><th>ID</th><th>Fecha</th><th>Cliente</th><th>Total</th><th>Acciones</th></tr>
            <?php foreach ($ventas as $venta): ?>
                <tr>
                    <td><?php echo $venta['id']; ?></td>
                    <td><?php echo $venta['fecha']; ?></td>
                    <td><?php echo $venta['nombre_cliente']; ?></td>
                    <td><?php echo $venta['total']; ?></td>
                    <td>
                        <a href="../Detalles_ventas/listar.php?id_venta=<?php echo $venta['id']; ?>">Ver Detalles</a>
                        <?php if ($rol != 4): ?>
                            <a href="editar.php?id=<?php echo $venta['id']; ?>">Editar</a>
                            <a href="eliminar.php?id=<?php echo $venta['id']; ?>">Eliminar</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>