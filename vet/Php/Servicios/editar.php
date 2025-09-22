<?php
session_start();
include '../db.php';
if (!isset($_SESSION['id_usuario']) || ($_SESSION['rol'] != 3 && $_SESSION['rol'] != 1)) {
    header('Location: ../inicio_sesion.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: listar.php');
    exit;
}
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM servicios WHERE id = ?");
$stmt->execute([$id]);
$serv = $stmt->fetch();

if (!$serv) {
    echo "Servicio no encontrado.";
    exit;
}

// Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];

    $stmt = $pdo->prepare("UPDATE servicios SET nombre = ?, precio = ? WHERE id = ?");
    $stmt->execute([$nombre, $precio, $id]);
    header('Location: listar.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editar Servicio</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="contenedor">
        <h1>Editar Servicio</h1>
        <form method="POST">
            <input type="text" name="nombre" value="<?php echo $serv['nombre']; ?>" required>
            <input type="number" step="0.01" name="precio" value="<?php echo $serv['precio']; ?>" required>
            <button type="submit">Actualizar</button>
        </form>
    </div>
</body>
</html>