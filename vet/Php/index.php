<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php'; // Incluye db.php desde la raíz Php/
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_usuario = $_POST['username'];
    $contrasena = $_POST['password'];

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
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css"> // Ruta desde raíz Php/
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Iniciar Sesión</button>
        </form>
        <a href="registro.php">Registrarse como Cliente</a>
    </div>
</body>
</html>