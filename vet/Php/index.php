<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';
session_start();

// Bloque para crear usuario administrador (ejecutar solo una vez)
$crear_admin = false; // Cambia a true para crear el usuario, luego vuelve a false
if ($crear_admin) {
    $nombre_usuario = 'leo';
    $contrasena = password_hash('1234', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre_usuario, contrasena, rol, nombre, correo, telefono) VALUES (?, ?, 1, ?, ?, ?)");
    $stmt->execute([$nombre_usuario, $contrasena, 'Leonardo', 'leo@example.com', '123456789']);
    echo "Usuario administrador 'leo' creado con éxito. Vuelve a false en \$crear_admin.";
    exit;
}

// Lógica de login existente...
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
    <link rel="stylesheet" href="css/style.css">
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