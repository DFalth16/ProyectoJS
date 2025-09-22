<?php
session_start();
include '../db.php';
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 1) {
    header('Location: ../inicio_sesion.php');
    exit;
}

// Create
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre_usuario, contrasena, rol, nombre, correo, telefono) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nombre_usuario, $contrasena, $rol, $nombre, $correo, $telefono]);
    header('Location: listar.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Crear Usuario</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="contenedor">
        <h1>Agregar Usuario</h1>
        <form method="POST">
            <input type="text" name="nombre_usuario" placeholder="Nombre de Usuario" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <select name="rol" required>
                <option value="1">Administrador</option>
                <option value="2">Veterinario</option>
                <option value="3">Recepcionista</option>
                <option value="4">Cliente</option>
            </select>
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="email" name="correo" placeholder="Correo" required>
            <input type="text" name="telefono" placeholder="Teléfono">
            <button type="submit">Agregar</button>
        </form>
    </div>
</body>
</html>