<?php
session_start();
include '../db.php';
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../inicio_sesion.php');
    exit;
}
$rol = $_SESSION['rol'];
$id_usuario = $_SESSION['id_usuario'];

// Read
if ($rol == 4) {
    $stmt = $pdo->prepare("SELECT h.*, m.nombre as nombre_mascota FROM historiales_medicos h JOIN mascotas m ON h.id_mascota = m.id WHERE m.id_dueno = ?");
    $stmt->execute([$id_usuario]);
} else {
    $stmt = $pdo->query("SELECT h.*, m.nombre as nombre_mascota FROM historiales_medicos h JOIN mascotas m ON h.id_mascota = m.id");
}
$historiales = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Listar Historiales Médicos</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="contenedor">
        <h1>Historiales Médicos</h1>
        <table>
            <tr><th>ID</th><th>Mascota</th><th>Fecha</th><th>Tipo</th><th>Diagnóstico</th><th>Acciones</th></tr>
            <?php foreach ($historiales as $hist): ?>
                <tr>
                    <td><?php echo $hist['id']; ?></td>
                    <td><?php echo $hist['nombre_mascota']; ?></td>
                    <td><?php echo $hist['fecha']; ?></td>
                    <td><?php echo $hist['tipo']; ?></td>
                    <td><?php echo $hist['diagnostico']; ?></td>
                    <td>
                        <?php if ($rol != 4): ?>
                            <a href="editar.php?id=<?php echo $hist['id']; ?>">Editar</a>
                            <a href="eliminar.php?id=<?php echo $hist['id']; ?>">Eliminar</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html> 