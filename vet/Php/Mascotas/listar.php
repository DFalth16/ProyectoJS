<?php
session_start();
include '../db.php';
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../index.php'); // Cambiado login
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
    <style>
        table img {
            max-width: 80px;
            max-height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h1>Mascotas</h1>
        <a href="crear.php" class="btn">+ Registrar Mascota</a>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>ID</th>
                <th>Foto</th>
                <th>Nombre</th>
                <th>Especie</th>
                <th>Raza</th>
                <th>Edad</th>
                <th>Peso</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($mascotas as $masc): ?>
                <tr>
                    <td><?php echo $masc['id']; ?></td>
                    <td>
                        <?php if (!empty($masc['foto']) && file_exists('../uploads/' . $masc['foto'])): ?>
                            <img src="../uploads/<?php echo $masc['foto']; ?>" alt="Foto de <?php echo $masc['nombre']; ?>">
                        <?php else: ?>
                            Sin foto
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($masc['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($masc['especie']); ?></td>
                    <td><?php echo htmlspecialchars($masc['raza']); ?></td>
                    <td><?php echo htmlspecialchars($masc['edad']); ?></td>
                    <td><?php echo htmlspecialchars($masc['peso']); ?></td>
                    <td>
                        <a href="editar.php?id=<?php echo $masc['id']; ?>">Editar</a>
                        <a href="eliminar.php?id=<?php echo $masc['id']; ?>" onclick="return confirm('¿Eliminar esta mascota?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
