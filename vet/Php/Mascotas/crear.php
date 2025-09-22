<?php
session_start();
include '../db.php';
if (!isset($_SESSION['id_usuario']) || ($_SESSION['rol'] != 4 && $_SESSION['rol'] != 1)) {
    header('Location: ../inicio_sesion.php');
    exit;
}
$id_dueno = $_SESSION['id_usuario'];

// Create
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $especie = $_POST['especie'];
    $raza = $_POST['raza'];
    $edad = $_POST['edad'];
    $peso = $_POST['peso'];

    $stmt = $pdo->prepare("INSERT INTO mascotas (nombre, especie, raza, edad, peso, id_dueno) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nombre, $especie, $raza, $edad, $peso, $id_dueno]);
    header('Location: listar.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Crear Mascota</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="contenedor">
        <h1>Registrar Mascota</h1>
        <form method="POST">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="especie" placeholder="Especie" required>
            <input type="text" name="raza" placeholder="Raza">
            <input type="number" name="edad" placeholder="Edad">
            <input type="number" step="0.01" name="peso" placeholder="Peso">
            <button type="submit">Registrar</button>
        </form>
    </div>
</body>
</html>