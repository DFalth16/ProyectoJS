<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header('Location: index.php');
    exit;
}
$vet_id = $_SESSION['user_id'];

// Update status
if (isset($_GET['confirm'])) {
    $id = $_GET['confirm'];
    $stmt = $pdo->prepare("UPDATE citas SET estado = 'confirmada' WHERE id = ? AND id_veterinario = ?");
    $stmt->execute([$id, $vet_id]);
    header('Location: appointments_vet.php');
    exit;
}
if (isset($_GET['cancel'])) {
    $id = $_GET['cancel'];
    $stmt = $pdo->prepare("UPDATE citas SET estado = 'cancelada' WHERE id = ? AND id_veterinario = ?");
    $stmt->execute([$id, $vet_id]);
    header('Location: appointments_vet.php');
    exit;
}
if (isset($_GET['done'])) {
    $id = $_GET['done'];
    $stmt = $pdo->prepare("UPDATE citas SET estado = 'realizada' WHERE id = ? AND id_veterinario = ?");
    $stmt->execute([$id, $vet_id]);
    header('Location: appointments_vet.php');
    exit;
}

// Read
$stmt = $pdo->prepare("SELECT c.*, m.nombre as mascota_nombre, u.nombre as cliente_nombre FROM citas c JOIN mascotas m ON c.id_mascota = m.id JOIN usuarios u ON m.id_dueno = u.id WHERE c.id_veterinario = ?");
$stmt->execute([$vet_id]);
$citas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Citas Asignadas</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Citas Asignadas</h1>
        <table>
            <tr><th>ID</th><th>Mascota</th><th>Cliente</th><th>Fecha/Hora</th><th>Estado</th><th>Acciones</th></tr>
            <?php foreach ($citas as $cita): ?>
                <tr>
                    <td><?php echo $cita['id']; ?></td>
                    <td><?php echo $cita['mascota_nombre']; ?></td>
                    <td><?php echo $cita['cliente_nombre']; ?></td>
                    <td><?php echo $cita['fecha_hora']; ?></td>
                    <td><?php echo $cita['estado']; ?></td>
                    <td>
                        <?php if ($cita['estado'] == 'pendiente'): ?>
                            <a href="?confirm=<?php echo $cita['id']; ?>">Confirmar</a>
                            <a href="?cancel=<?php echo $cita['id']; ?>">Cancelar</a>
                        <?php elseif ($cita['estado'] == 'confirmada'): ?>
                            <a href="?done=<?php echo $cita['id']; ?>">Marcar como Realizada</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>