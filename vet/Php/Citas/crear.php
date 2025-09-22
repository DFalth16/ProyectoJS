<?php
session_start();
include '../db.php';
if (!isset($_SESSION['id_usuario']) || ($_SESSION['rol'] != 4 && $_SESSION['rol'] != 3 && $_SESSION['rol'] != 1)) {
    header('Location: ../index.php');
    exit;
}
$id_usuario = $_SESSION['id_usuario'];

if ($_SESSION['rol'] == 4) {
    $stmt = $pdo->prepare("SELECT * FROM mascotas WHERE id_dueno = ?");
    $stmt->execute([$id_usuario]);
    $mascotas = $stmt->fetchAll();
} else {
    $stmt = $pdo->query("SELECT * FROM mascotas");
    $mascotas = $stmt->fetchAll();
}
$stmt = $pdo->query("SELECT * FROM usuarios WHERE rol = 2");
$vets = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_mascota = $_POST['id_mascota'];
    $id_veterinario = $_POST['id_veterinario'];
    $fecha_hora = $_POST['fecha_hora'];

    $stmt = $pdo->prepare("SELECT * FROM citas WHERE id_veterinario = ? AND fecha_hora = ?");
    $stmt->execute([$id_veterinario, $fecha_hora]);
    if ($stmt->rowCount() == 0) {
        $stmt = $pdo->prepare("INSERT INTO citas (id_mascota, id_veterinario, fecha_hora) VALUES (?, ?, ?)");
        $stmt->execute([$id_mascota, $id_veterinario, $fecha_hora]);
        header('Location: listar.php');
        exit;
    } else {
        $error = "Horario no disponible.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Crear Cita</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Agendar Cita</h1>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST">
            <select name="id_mascota" required>
                <?php foreach ($mascotas as $masc): ?>
                    <option value="<?php echo $masc['id']; ?>"><?php echo $masc['nombre']; ?></option>
                <?php endforeach; ?>
            </select>
            <select name="id_veterinario" required>
                <?php foreach ($vets as $vet): ?>
                    <option value="<?php echo $vet['id']; ?>"><?php echo $vet['nombre']; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="datetime-local" name="fecha_hora" required>
            <button type="submit">Agendar</button>
        </form>
    </div>
</body>
</html>