<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre_usuario, contrasena, rol, nombre, correo, telefono) VALUES (?, ?, 4, ?, ?, ?)");
    $stmt->execute([$nombre_usuario, $contrasena, $nombre, $correo, $telefono]);
    header('Location: inicio_sesion.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="contenedor">
        <h1>Registro de Cliente</h1>
        <form method="POST">
            <input type="text" name="nombre_usuario" placeholder="Nombre de Usuario" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="email" name="correo" placeholder="Correo" required>
            <input type="text" name="telefono" placeholder="Teléfono">
            <button type="submit">Registrarse</button>
        </form>
    </div>
</body>
</html>