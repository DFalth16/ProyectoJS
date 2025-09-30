<?php
session_start();
include '../db.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../index.php');
    exit;
}

$id_dueno = $_SESSION['id_usuario'];

// Capturar filtros desde GET
$estadoFiltro = $_GET['estado'] ?? '';
$tipoFecha = $_GET['tipo_fecha'] ?? '';
$nombreMascota = $_GET['mascota'] ?? '';

// Construir consulta base
$query = "
    SELECT c.id AS cita_id, c.fecha_hora, c.estado, m.nombre AS mascota, m.especie, m.raza, u.nombre AS veterinario
    FROM citas c
    INNER JOIN mascotas m ON c.id_mascota = m.id
    INNER JOIN usuarios u ON c.id_veterinario = u.id
    WHERE m.id_dueno = ?
";

$params = [$id_dueno];

// Filtrar por estado
if ($estadoFiltro && in_array($estadoFiltro, ['pendiente','confirmada','cancelada','realizada'])) {
    $query .= " AND c.estado = ?";
    $params[] = $estadoFiltro;
}

// Filtrar por fecha
if ($tipoFecha == 'futuras') {
    $query .= " AND c.fecha_hora >= NOW()";
} elseif ($tipoFecha == 'pasadas') {
    $query .= " AND c.fecha_hora < NOW()";
}

// Filtrar por nombre de mascota
if ($nombreMascota) {
    $query .= " AND m.nombre LIKE ?";
    $params[] = "%$nombreMascota%";
}

$query .= " ORDER BY c.fecha_hora DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$citas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Citas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/mis_citas.css">
</head>
<body>
<div class="container my-5">
    <h1 class="text-center mb-4">Mis Citas</h1>

    <!-- Filtros -->
    <form method="get" class="row g-3 mb-4 align-items-end">
        <div class="col-md-3">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-select" name="estado" id="estado">
                <option value="">Todos</option>
                <option value="pendiente" <?= $estadoFiltro=='pendiente'?'selected':'' ?>>Pendiente</option>
                <option value="confirmada" <?= $estadoFiltro=='confirmada'?'selected':'' ?>>Confirmada</option>
                <option value="cancelada" <?= $estadoFiltro=='cancelada'?'selected':'' ?>>Cancelada</option>
                <option value="realizada" <?= $estadoFiltro=='realizada'?'selected':'' ?>>Realizada</option>
            </select>
        </div>

        <div class="col-md-3">
            <label for="tipo_fecha" class="form-label">Fecha</label>
            <select class="form-select" name="tipo_fecha" id="tipo_fecha">
                <option value="">Todas</option>
                <option value="futuras" <?= $tipoFecha=='futuras'?'selected':'' ?>>Futuras</option>
                <option value="pasadas" <?= $tipoFecha=='pasadas'?'selected':'' ?>>Pasadas</option>
            </select>
        </div>

        <div class="col-md-3">
            <label for="mascota" class="form-label">Mascota</label>
            <input type="text" class="form-control" name="mascota" id="mascota" placeholder="Nombre mascota" value="<?= htmlspecialchars($nombreMascota) ?>">
        </div>

        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
        </div>
    </form>

    <!-- Tabla de Citas -->
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-primary">
                <tr>
                    <th>ID Cita</th>
                    <th>Mascota</th>
                    <th>Especie</th>
                    <th>Raza</th>
                    <th>Veterinario</th>
                    <th>Fecha y Hora</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if($citas): ?>
                    <?php foreach ($citas as $cita): ?>
                        <tr>
                            <td><?= $cita['cita_id'] ?></td>
                            <td><?= htmlspecialchars($cita['mascota']) ?></td>
                            <td><?= htmlspecialchars($cita['especie']) ?></td>
                            <td><?= htmlspecialchars($cita['raza']) ?></td>
                            <td><?= htmlspecialchars($cita['veterinario']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($cita['fecha_hora'])) ?></td>
                            <td>
                                <?php
                                $badge = match($cita['estado']){
                                    'pendiente'=>'warning',
                                    'confirmada'=>'success',
                                    'cancelada'=>'danger',
                                    'realizada'=>'secondary',
                                    default=>'light'
                                };
                                ?>
                                <span class="badge bg-<?= $badge ?>"><?= ucfirst($cita['estado']) ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center">No hay citas que coincidan con los filtros.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <a href="../panel.php" class="btn btn-secondary mt-3">Volver al Panel</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
