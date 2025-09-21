<?php
session_start();
include '../db.php';
if (!isset($_SESSION['id_usuario']) || ($_SESSION['rol'] != 4 && $_SESSION['rol'] != 1)) {
    header('Location: ../inicio_sesion.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: listar.php');
    exit;
}
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM mascotas WHERE id = ?");
$stmt->execute([$id]);
$masc = $stmt->fetch();

if (!$masc || ($masc['id_dueno'] != $_SESSION['id_usuario'] && $_SESSION['rol'] != 1)) {
    echo "Mascota no encontrada o no autorizada.";
    exit;
}

// Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $especie = $_POST['especie'];
    $raza = $_POST['raza'];
    $edad = $_POST['edad'];
    $peso = $_POST['peso'];

    $stmt = $pdo->prepare("UPDATE mascotas SET nombre = ?, especie = ?, raza = ?, edad = ?, peso = ? WHERE id = ?");
    $stmt->execute([$nombre, $especie, $raza, $edad, $peso, $id]);
    header('Location: listar.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editar Mascota</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="contenedor">
        <h1>Editar Mascota</h1>
        <form method="POST">
            <input type="text" name="nombre" value="<?php echo $masc['nombre']; ?>" required>
            <input type="text" name="especie" value="<?php echo $masc['especie']; ?>" required>
            <input type="text" name="raza" value="<?php echo $masc['raza']; ?>">
            <input type="number" name="edad" value="<?php echo $masc['edad']; ?>">
            <input type="number" step="0.01" name="peso" value="<?php echo $masc['peso']; ?>">
            <button type="submit">Actualizar</button>
        </form>
    </div>
</body>
</html>