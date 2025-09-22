<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasena = $_POST['contrasena'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nombre_usuario = ?");
    $stmt->execute([$nombre_usuario]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
        $_SESSION['id_usuario'] = $usuario['id'];
        $_SESSION['rol'] = $usuario['rol'];
        header('Location: panel.php');
        exit;
    } else {
        $error = "Credenciales inválidas.";
    }
}

if (isset($_SESSION['id_usuario'])) {
    header('Location: panel.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Inicio de Sesión</h1>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="nombre_usuario" placeholder="Nombre de Usuario" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <button type="submit">Iniciar Sesión</button>
        </form>
        <a href="registro.php">Registrarse como Cliente</a>
    </div>
</body>
</html>