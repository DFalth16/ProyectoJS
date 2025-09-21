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

$stmt = $pdo->prepare("SELECT * FROM citas WHERE id = ?");
$stmt->execute([$id]);
$cita = $stmt->fetch();

if (!$cita) {
    echo "Cita no encontrada.";
    exit;
}

// Get mascotas y vets
$stmt = $pdo->query("SELECT * FROM mascotas");
$mascotas = $stmt->fetchAll();
$stmt = $pdo->query("SELECT * FROM usuarios WHERE rol = 2");
$vets = $stmt->fetchAll();

// Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_mascota = $_POST['id_mascota'];
    $id_veterinario = $_POST['id_veterinario'];
    $fecha_hora = $_POST['fecha_hora'];
    $estado = $_POST['estado'];

    $stmt = $pdo->prepare("UPDATE citas SET id_mascota = ?, id_veterinario = ?, fecha_hora = ?, estado = ? WHERE id = ?");
    $stmt->execute([$id_mascota, $id_veterinario, $fecha_hora, $estado, $id]);
    header('Location: listar.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editar Cita</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="contenedor">
        <h1>Editar Cita</h1>
        <form method="POST">
            <select name="id_mascota" required>
                <?php foreach ($mascotas as $masc): ?>
                    <option value="<?php echo $masc['id']; ?>" <?php if ($masc['id'] == $cita['id_mascota']) echo 'selected'; ?>><?php echo $masc['nombre']; ?></option>
                <?php endforeach; ?>
            </select>
            <select name="id_veterinario" required>
                <?php foreach ($vets as $vet): ?>
                    <option value="<?php echo $vet['id']; ?>" <?php if ($vet['id'] == $cita['id_veterinario']) echo 'selected'; ?>><?php echo $vet['nombre']; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="datetime-local" name="fecha_hora" value="<?php echo date('Y-m-d\TH:i', strtotime($cita['fecha_hora'])); ?>" required>
            <select name="estado">
                <option value="pendiente" <?php if ($cita['estado'] == 'pendiente') echo 'selected'; ?>>Pendiente</option>
                <option value="confirmada" <?php if ($cita['estado'] == 'confirmada') echo 'selected'; ?>>Confirmada</option>
                <option value="cancelada" <?php if ($cita['estado'] == 'cancelada') echo 'selected'; ?>>Cancelada</option>
                <option value="realizada" <?php if ($cita['estado'] == 'realizada') echo 'selected'; ?>>Realizada</option>
            </select>
            <button type="submit">Actualizar</button>
        </form>
    </div>
</body>
</html>