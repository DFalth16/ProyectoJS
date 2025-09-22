<?php
session_start();
include '../db.php';
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 1) {
    header('Location: ../inicio_sesion.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM usuarios");
$usuarios = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Listar Usuarios</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="contenedor">
        <h1>Usuarios</h1>
        <table>
            <tr><th>ID</th><th>Usuario</th><th>Rol</th><th>Nombre</th><th>Correo</th><th>Acciones</th></tr>
            <?php foreach ($usuarios as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['nombre_usuario']; ?></td>
                    <td><?php echo $user['rol']; ?></td>
                    <td><?php echo $user['nombre']; ?></td>
                    <td><?php echo $user['correo']; ?></td>
                    <td>
                        <a href="editar.php?id=<?php echo $user['id']; ?>">Editar</a>
                        <a href="eliminar.php?id=<?php echo $user['id']; ?>">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>