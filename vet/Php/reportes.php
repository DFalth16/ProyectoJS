<?php
// reportes.php
session_start();
include 'db.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 1) {
    header('Location: index.php');
    exit;
}

$ventas = [];
$msg = '';
// valores para mantener en el formulario
$id_cliente = $_POST['id_cliente'] ?? '';
$fecha_inicio = $_POST['fecha_inicio'] ?? '';
$fecha_fin = $_POST['fecha_fin'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $wheres = [];
    $params = [];

    // id_cliente (si se envía, aceptamos solo números)
    if (trim($id_cliente) !== '') {
        // si viene algo no numérico, lo ignoramos; si quieres bloquear, podrías validar
        $id_cliente_int = intval($id_cliente);
        if ($id_cliente_int > 0) {
            $wheres[] = "v.id_cliente = ?";
            $params[] = $id_cliente_int;
        } else {
            $msg = "El ID de cliente debe ser numérico. Se ignoró el filtro.";
        }
    }

    // fecha_inicio: convertir a inicio del día
    if (!empty($fecha_inicio)) {
        // Esperamos formato YYYY-MM-DD desde <input type="date">
        $d = DateTime::createFromFormat('Y-m-d', $fecha_inicio);
        if ($d !== false) {
            $fecha_inicio_sql = $d->format('Y-m-d') . ' 00:00:00';
            $wheres[] = "v.fecha >= ?";
            $params[] = $fecha_inicio_sql;
        } else {
            $msg = "Fecha inicio inválida. Se ignoró el filtro.";
        }
    }

    // fecha_fin: convertir a fin del día
    if (!empty($fecha_fin)) {
        $d2 = DateTime::createFromFormat('Y-m-d', $fecha_fin);
        if ($d2 !== false) {
            $fecha_fin_sql = $d2->format('Y-m-d') . ' 23:59:59';
            $wheres[] = "v.fecha <= ?";
            $params[] = $fecha_fin_sql;
        } else {
            $msg = "Fecha fin inválida. Se ignoró el filtro.";
        }
    }

    $sql = "SELECT v.id, v.fecha, v.id_cliente, v.total, u.nombre AS cliente_nombre
            FROM ventas v
            LEFT JOIN usuarios u ON v.id_cliente = u.id";

    if (!empty($wheres)) {
        $sql .= " WHERE " . implode(" AND ", $wheres);
    }

    $sql .= " ORDER BY v.fecha DESC";

    // preparar y ejecutar
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$ventas) {
        $msg = $msg ? $msg : "No se encontraron ventas con los criterios indicados.";
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Reportes - Ventas</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="card shadow-sm">
      <div class="card-body">
        <h1 class="card-title">Reportes</h1>

        <h4 class="mt-4">Reporte de Ventas</h4>

        <?php if ($msg): ?>
          <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <form method="post" class="row g-3 mb-4">
          <div class="col-md-4">
            <label class="form-label">ID Cliente</label>
            <input type="text" name="id_cliente" value="<?= htmlspecialchars($id_cliente) ?>" class="form-control" placeholder="ID Cliente (opcional)">
          </div>
          <div class="col-md-3">
            <label class="form-label">Fecha inicio</label>
            <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($fecha_inicio) ?>" class="form-control">
          </div>
          <div class="col-md-3">
            <label class="form-label">Fecha fin</label>
            <input type="date" name="fecha_fin" value="<?= htmlspecialchars($fecha_fin) ?>" class="form-control">
          </div>
          <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-success w-100">Generar</button>
          </div>
        </form>

        <!-- Tabla resultados -->
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Cliente (ID - nombre)</th>
                <th class="text-end">Total</th>
              </tr>
            </thead>
            <tbody>
            <?php if (empty($ventas)): ?>
              <tr>
                <td colspan="4" class="text-center">No hay ventas para mostrar.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($ventas as $v): ?>
                <tr>
                  <td><?= htmlspecialchars($v['id']) ?></td>
                  <td><?= htmlspecialchars($v['fecha']) ?></td>
                  <td>
                    <?= htmlspecialchars($v['id_cliente']) ?>
                    <?php if (!empty($v['cliente_nombre'])): ?>
                      - <?= htmlspecialchars($v['cliente_nombre']) ?>
                    <?php else: ?>
                      - <em>Sin nombre</em>
                    <?php endif; ?>
                  </td>
                  <td class="text-end"><?= number_format($v['total'], 2, ',', '.') ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
