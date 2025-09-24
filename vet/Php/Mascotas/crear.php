<?php
session_start();
include '../db.php';
if (!isset($_SESSION['id_usuario']) || ($_SESSION['rol'] != 4 && $_SESSION['rol'] != 1)) {
    header('Location: ../index.php'); // cambio de login
    exit;
}
$id_dueno = $_SESSION['id_usuario'];

// Crear mascota
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $especie = $_POST['especie'];
    $raza = $_POST['raza'];
    $edad = $_POST['edad'];
    $peso = $_POST['peso'];

    // Manejo de la foto
    $foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nombreArchivo = uniqid('mascota_') . '.' . $ext;
        $rutaDestino = '../uploads/' . $nombreArchivo;

        // Crear carpeta uploads si no existe
        if (!file_exists('../uploads')) {
            mkdir('../uploads', 0777, true);
        }

        move_uploaded_file($_FILES['foto']['tmp_name'], $rutaDestino);
        $foto = $nombreArchivo;
    }

    $stmt = $pdo->prepare("INSERT INTO mascotas (nombre, especie, raza, edad, peso, foto, id_dueno) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nombre, $especie, $raza, $edad, $peso, $foto, $id_dueno]);

    header('Location: listar.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registrar Mascota</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="contenedor">
        <h1>Registrar Mascota</h1>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="especie" placeholder="Especie" required>
            <input type="text" name="raza" placeholder="Raza">
            <input type="number" name="edad" placeholder="Edad">
            <input type="number" step="0.01" name="peso" placeholder="Peso">
            <input type="file" name="foto" accept="image/*">
            <button type="submit">Registrar</button>
        </form>
    </div>
</body>
</html>
