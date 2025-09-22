<?php
session_start();
include '../db.php';
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../inicio_sesion.php');
    exit;
}
$rol = $_SESSION['rol'];
$id_dueno = $_SESSION['id_usuario'];

if ($rol == 4) {
    $stmt = $pdo->prepare("SELECT * FROM mascotas WHERE id_dueno = ?");
    $stmt->execute([$id_dueno]);
} else {
    $stmt = $pdo->query("SELECT * FROM mascotas");
}
$mascotas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Listar Mascotas</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="contenedor">
        <h1>Mascotas</h1>
        <table>
            <tr><th>ID</th><th>Nombre</th><th>Especie</th><th>Raza</th><th>Edad</th><th>Peso</th><th>Acciones</th></tr>
            <?php foreach ($mascotas as $masc): ?>
                <tr>
                    <td><?php echo $masc['id']; ?></td>
                    <td><?php echo $masc['nombre']; ?></td>
                    <td><?php echo $masc['especie']; ?></td>
                    <td><?php echo $masc['raza']; ?></td>
                    <td><?php echo $masc['edad']; ?></td>
                    <td><?php echo $masc['peso']; ?></td>
                    <td>
                        <a href="editar.php?id=<?php echo $masc['id']; ?>">Editar</a>
                        <a href="eliminar.php?id=<?php echo $masc['id']; ?>">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>