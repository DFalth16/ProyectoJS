<?php
session_start();
include '../db.php';
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../inicio_sesion.php');
    exit;
}
$rol = $_SESSION['rol'];
$id_usuario = $_SESSION['id_usuario'];

// Update estado if vet or rec
if (isset($_GET['confirmar'])) {
    $id = $_GET['confirmar'];
    $stmt = $pdo->prepare("UPDATE citas SET estado = 'confirmada' WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: listar.php');
    exit;
}
// Similar para cancelar, realizada

// Read
if ($rol == 4) {
    $stmt = $pdo->prepare("SELECT c.*, m.nombre as nombre_mascota, u.nombre as nombre_vet FROM citas c JOIN mascotas m ON c.id_mascota = m.id JOIN usuarios u ON c.id_veterinario = u.id WHERE m.id_dueno = ?");
    $stmt->execute([$id_usuario]);
} elseif ($rol == 2) {
    $stmt = $pdo->prepare("SELECT c.*, m.nombre as nombre_mascota, cl.nombre as nombre_cliente FROM citas c JOIN mascotas m ON c.id_mascota = m.id JOIN usuarios cl ON m.id_dueno = cl.id WHERE c.id_veterinario = ?");
    $stmt->execute([$id_usuario]);
} else {
    $stmt = $pdo->query("SELECT c.*, m.nombre as nombre_mascota, cl.nombre as nombre_cliente, u.nombre as nombre_vet FROM citas c JOIN mascotas m ON c.id_mascota = m.id JOIN usuarios cl ON m.id_dueno = cl.id JOIN usuarios u ON c.id_veterinario = u.id");
}
$citas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Listar Citas</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="contenedor">
        <h1>Citas</h1>
        <table>
            <tr><th>ID</th><th>Mascota</th><th>Veterinario</th><th>Fecha/Hora</th><th>Estado</th><th>Acciones</th></tr>
            <?php foreach ($citas as $cita): ?>
                <tr>
                    <td><?php echo $cita['id']; ?></td>
                    <td><?php echo $cita['nombre_mascota']; ?></td>
                    <td><?php echo $cita['nombre_vet']; ?></td>
                    <td><?php echo $cita['fecha_hora']; ?></td>
                    <td><?php echo $cita['estado']; ?></td>
                    <td>
                        <a href="editar.php?id=<?php echo $cita['id']; ?>">Editar</a>
                        <a href="eliminar.php?id=<?php echo $cita['id']; ?>">Eliminar</a>
                        <?php if ($rol == 2 || $rol == 3 || $rol == 1): ?>
                            <a href="?confirmar=<?php echo $cita['id']; ?>">Confirmar</a>
                            <a href="?cancelar=<?php echo $cita['id']; ?>">Cancelar</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>