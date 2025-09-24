<?php
session_start();
include '../db.php';

// Verificar sesión y rol
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 1) {
    header('Location: ../index.php'); // Redirige al menú principal si no es admin
    exit;
}

// Obtener usuarios de la base de datos
$stmt = $pdo->query("SELECT * FROM usuarios ORDER BY id DESC");
$usuarios = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios - Panel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <h1 class="mb-4">Usuarios</h1>

    <!-- Botón volver al menú -->
    <a href="../index.php" class="btn btn-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Volver al Menú
    </a>

    <!-- Botón crear usuario -->
    <a href="crear.php" class="btn btn-success mb-3">
        <i class="bi bi-person-plus-fill"></i> Crear Usuario
    </a>

    <!-- Tabla de usuarios -->
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= htmlspecialchars($user['nombre_usuario']) ?></td>
                            <td>
                                <?php
                                    $roles = [1=>'Administrador',2=>'Veterinario',3=>'Recepcionista',4=>'Cliente'];
                                    echo $roles[$user['rol']] ?? 'Desconocido';
                                ?>
                            </td>
                            <td><?= htmlspecialchars($user['nombre']) ?></td>
                            <td><?= htmlspecialchars($user['correo']) ?></td>
                            <td><?= htmlspecialchars($user['telefono']) ?></td>
                            <td>
                                <a href="editar.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil-fill"></i> Editar
                                </a>
                                <a href="eliminar.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este usuario?')">
                                    <i class="bi bi-trash-fill"></i> Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($usuarios)): ?>
                        <tr>
                            <td colspan="7" class="text-center">No hay usuarios registrados</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
