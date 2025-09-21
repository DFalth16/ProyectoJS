<?php
session_start();
include '../db.php';
if (!isset($_SESSION['id_usuario']) || ($_SESSION['rol'] != 3 && $_SESSION['rol'] != 1)) {
    header('Location: ../inicio_sesion.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM servicios");
$servicios = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Listar Servicios</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="contenedor">
        <h1>Servicios</h1>
        <table>
            <tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Acciones</th></tr>
            <?php foreach ($servicios as $serv): ?>
                <tr>
                    <td><?php echo $serv['id']; ?></td>
                    <td><?php echo $serv['nombre']; ?></td>
                    <td><?php echo $serv['precio']; ?></td>
                    <td>
                        <a href="editar.php?id=<?php echo $serv['id']; ?>">Editar</a>
                        <a href="eliminar.php?id=<?php echo $serv['id']; ?>">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>