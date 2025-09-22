<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: inicio_sesion.php');
    exit;
} else {
    header('Location: panel.php');
    exit;
}
?>

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Credenciales inválidas.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="../Css/style.css">
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form method="POST">
            <input type="text" name="username" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Iniciar Sesión</button>
        </form>
        <a href="register.php">Registrarse como Cliente</a>
    </div>
</body>
</html>