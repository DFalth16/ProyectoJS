<?php
session_start();
include '../db.php';
if (!isset($_SESSION['id_usuario']) || ($_SESSION['rol'] != 2 && $_SESSION['rol'] != 1)) {
    header('Location: ../inicio_sesion.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: listar.php');
    exit;
}
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM historiales_medicos WHERE id = ?");
$stmt->execute([$id]);
$hist = $stmt->fetch();

if (!$hist) {
    echo "Historial no encontrado.";
    exit;
}

// Get mascotas
$stmt = $pdo->query("SELECT * FROM mascotas");
$mascotas = $stmt->fetchAll();

// Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_mascota = $_POST['id_mascota'];
    $diagnostico = $_POST['diagnostico'];
    $tratamiento = $_POST['tratamiento'];
    $vacunas = $_POST['vacunas'];
    $evolucion = $_POST['evolucion'];
    $tipo = $_POST['tipo'];

    $stmt = $pdo->prepare("UPDATE historiales_medicos SET id_mascota = ?, diagnostico = ?, tratamiento = ?, vacunas = ?, evolucion = ?, tipo = ? WHERE id = ?");
    $stmt->execute([$id_mascota, $diagnostico, $tratamiento, $vacunas, $evolucion, $tipo, $id]);
    header('Location: listar.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editar Historial Médico</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="contenedor">
        <h1>Editar Historial</h1>
        <form method="POST">
            <select name="id_mascota" required>
                <?php foreach ($mascotas as $masc): ?>
                    <option value="<?php echo $masc['id']; ?>" <?php if ($masc['id'] == $hist['id_mascota']) echo 'selected'; ?>><?php echo $masc['nombre']; ?></option>
                <?php endforeach; ?>
            </select>
            <textarea name="diagnostico"><?php echo $hist['diagnostico']; ?></textarea>
            <textarea name="tratamiento"><?php echo $hist['tratamiento']; ?></textarea>
            <textarea name="vacunas"><?php echo $hist['vacunas']; ?></textarea>
            <textarea name="evolucion"><?php echo $hist['evolucion']; ?></textarea>
            <select name="tipo">
                <option value="vacunacion" <?php if ($hist['tipo'] == 'vacunacion') echo 'selected'; ?>>Vacunación</option>
                <!-- Opciones similares -->
            </select>
            <button type="submit">Actualizar</button>
        </form>
    </div>
</body>
</html>