<?php
session_start();
include '../db.php';
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../inicio_sesion.php');
    exit;
}

if (!isset($_GET['id_venta'])) {
    echo "ID de venta requerido.";
    exit;
}
$id_venta = (int) $_GET['id_venta'];

// Obtener la venta (con nombre del cliente)
$stmtVenta = $pdo->prepare("
    SELECT v.*, u.nombre AS nombre_cliente
    FROM ventas v
    JOIN usuarios u ON v.id_cliente = u.id
    WHERE v.id = ?
");
$stmtVenta->execute([$id_venta]);
$venta = $stmtVenta->fetch(PDO::FETCH_ASSOC);

if (!$venta) {
    echo "Venta no encontrada.";
    exit;
}

// Obtener detalles de la venta con el nombre del item
$stmt = $pdo->prepare("
    SELECT dv.id, dv.tipo_item, dv.id_item, dv.cantidad, dv.precio,
           CASE 
               WHEN dv.tipo_item = 'producto' THEN p.nombre
               WHEN dv.tipo_item = 'servicio' THEN s.nombre
               ELSE 'Desconocido'
           END AS nombre_item
    FROM detalles_ventas dv
    LEFT JOIN productos p ON dv.tipo_item = 'producto' AND dv.id_item = p.id
    LEFT JOIN servicios s ON dv.tipo_item = 'servicio' AND dv.id_item = s.id
    WHERE dv.id_venta = ?
    ORDER BY dv.id ASC
");
$stmt->execute([$id_venta]);
$detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles de Venta #<?= htmlspecialchars($id_venta) ?> — <?= htmlspecialchars($venta['nombre_cliente']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* fondo con imagen (ruta relativa desde Detalles_ventas/detalleVenta.php hacia /img/imagen1.png) */
        body {
            min-height: 100vh;
            background:
                linear-gradient(rgba(255,255,255,0.72), rgba(255,255,255,0.72)),
                url('../img/imagen1.png') center / cover no-repeat fixed;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            margin: 0;
        }
        .page-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
        }
        .card-sheet {
            width: 100%;
            max-width: 1100px;
            background-color: rgba(255,255,255,0.96);
            border-radius: 12px;
            box-shadow: 0 12px 40px rgba(11,20,34,0.10);
            padding: 28px;
        }
        .sale-meta .badge { font-size: .9rem; }
        table th, table td { vertical-align: middle; }
        .muted { color: #6c757d; }
    </style>
</head>
<body>
  <div class="page-wrap">
    <div class="card-sheet">
      <div class="d-flex flex-column flex-md-row align-items-start justify-content-between mb-3 gap-3">
        <div>
          <h3 class="mb-1">Detalles de Venta <small class="text-muted">#<?= htmlspecialchars($id_venta) ?></small></h3>
          <div class="muted">Cliente: <strong><?= htmlspecialchars($venta['nombre_cliente']) ?></strong></div>
        </div>

        <div class="text-md-end sale-meta">
          <div class="muted">Fecha:</div>
          <div><strong><?= date('d/m/Y H:i', strtotime($venta['fecha'])) ?></strong></div>
          <div class="mt-2 muted">Total:</div>
          <div><strong>$<?= number_format($venta['total'], 2) ?></strong></div>
        </div>
      </div>

      <?php if (empty($detalles)): ?>
        <div class="alert alert-warning">No hay detalles registrados para esta venta.</div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Tipo</th>
                <th>Nombre del Item</th>
                <th class="text-center">Cantidad</th>
                <th class="text-end">Precio</th>
                <th class="text-end">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              <?php $suma = 0; foreach ($detalles as $det): 
                $subtotal = $det['cantidad'] * $det['precio'];
                $suma += $subtotal;
              ?>
                <tr>
                  <td><?= htmlspecialchars($det['id']) ?></td>
                  <td><?= htmlspecialchars(ucfirst($det['tipo_item'])) ?></td>
                  <td><?= htmlspecialchars($det['nombre_item']) ?></td>
                  <td class="text-center"><?= htmlspecialchars($det['cantidad']) ?></td>
                  <td class="text-end">$<?= number_format($det['precio'], 2) ?></td>
                  <td class="text-end">$<?= number_format($subtotal, 2) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="5" class="text-end fw-bold">Total calculado:</td>
                <td class="text-end fw-bold">$<?= number_format($suma, 2) ?></td>
              </tr>
            </tfoot>
          </table>
        </div>
      <?php endif; ?>

      <div class="mt-3 d-flex gap-2">
        <a href="../Ventas/NotasVentas.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Volver a Ventas</a>
        <a href="#" onclick="window.print(); return false;" class="btn btn-outline-dark ms-auto"><i class="bi bi-printer"></i> Imprimir</a>
      </div>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
