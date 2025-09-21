<?php
session_start();
include '../db.php';
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 1) {
    header('Location: ../inicio_sesion.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: listar.php');
    exit;
}
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    echo "Usuario no encontrado.";
    exit;
}

// Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $rol = $_POST['rol'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $contrasena = !empty($_POST['contrasena']) ? password_hash($_POST['contrasena'], PASSWORD_DEFAULT) : $user['contrasena'];

    $stmt = $pdo->prepare("UPDATE usuarios SET nombre_usuario = ?, contrasena = ?, rol = ?, nombre = ?, correo = ?, telefono = ? WHERE id = ?");
    $stmt->execute([$nombre_usuario, $contrasena, $rol, $nombre, $correo, $telefono, $id]);
    header('Location: listar.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="contenedor">
        <h1>Editar Usuario</h1>
        <form method="POST">
            <input type="text" name="nombre_usuario" value="<?php echo $user['nombre_usuario']; ?>" required>
            <input type="password" name="contrasena" placeholder="Nueva Contraseña (opcional)">
            <select name="rol" required>
                <option value="1" <?php if ($user['rol'] == 1) echo 'selected'; ?>>Administrador</option>
                <!-- Opciones similares -->
            </select>
            <input type="text" name="nombre" value="<?php echo $user['nombre']; ?>" required>
            <input type="email" name="correo" value="<?php echo $user['correo']; ?>" required>
            <input type="text" name="telefono" value="<?php echo $user['telefono']; ?>">
            <button type="submit">Actualizar</button>
        </form>
    </div>
</body>
</html>