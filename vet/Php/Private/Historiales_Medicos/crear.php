<?php
session_start();
include '../db.php';
if (!isset($_SESSION['id_usuario']) || ($_SESSION['rol'] != 2 && $_SESSION['rol'] != 1)) {
    header('Location: ../inicio_sesion.php');
    exit;
}

// Get mascotas
$stmt = $pdo->query("SELECT * FROM mascotas");
$mascotas = $stmt->fetchAll();

// Create
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_mascota = $_POST['id_mascota'];
    $fecha = date('Y-m-d H:i:s');
    $diagnostico = $_POST['diagnostico'];
    $tratamiento = $_POST['tratamiento'];
    $vacunas = $_POST['vacunas'];
    $evolucion = $_POST['evolucion'];
    $tipo = $_POST['tipo'];

    $stmt = $pdo->prepare("INSERT INTO historiales_medicos (id_mascota, fecha, diagnostico, tratamiento, vacunas, evolucion, tipo) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$id_mascota, $fecha, $diagnostico, $tratamiento, $vacunas, $evolucion, $tipo]);
    header('Location: listar.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Crear Historial Médico</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="contenedor">
        <h1>Registrar Historial Médico</h1>
        <form method="POST">
            <select name="id_mascota" required>
                <?php foreach ($mascotas as $masc): ?>
                    <option value="<?php echo $masc['id']; ?>"><?php echo $masc['nombre']; ?></option>
                <?php endforeach; ?>
            </select>
            <textarea name="diagnostico" placeholder="Diagnóstico"></textarea>
            <textarea name="tratamiento" placeholder="Tratamiento"></textarea>
            <textarea name="vacunas" placeholder="Vacunas"></textarea>
            <textarea name="evolucion" placeholder="Evolución"></textarea>
            <select name="tipo" required>
                <option value="vacunacion">Vacunación</option>
                <option value="internacion">Internación</option>
                <option value="revision">Revisión</option>
                <option value="desparasitacion">Desparasitación</option>
                <option value="peluqueria">Peluquería</option>
                <option value="otro">Otro</option>
            </select>
            <button type="submit">Registrar</button>
        </form>
    </div>
</body>
</html>