<?php
session_start();
include '../db.php';
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../inicio_sesion.php');
    exit;
}

$rol = $_SESSION['rol'];
$id_usuario = $_SESSION['id_usuario'];

// Obtener ventas: si cliente, solo sus ventas
if ($rol == 4) {
    $stmt = $pdo->prepare("
        SELECT v.*, u.nombre as nombre_cliente 
        FROM ventas v 
        JOIN usuarios u ON v.id_cliente = u.id 
        WHERE v.id_cliente = ?
        ORDER BY v.fecha DESC
    ");
    $stmt->execute([$id_usuario]);
} else {
    $stmt = $pdo->query("
        SELECT v.*, u.nombre as nombre_cliente 
        FROM ventas v 
        JOIN usuarios u ON v.id_cliente = u.id
        ORDER BY v.fecha DESC
    ");
}

$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Notas de Venta - Amigos de Cuatro Patas</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* Fondo con imagen1.png más claro y nítido (overlay sutil) */
        body {
            min-height: 100vh;
            margin: 0;
            background:
                linear-gradient(rgba(255,255,255,0.45), rgba(255,255,255,0.45)),
                url('../img/imagen1.png') center / cover no-repeat fixed;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Panel interior */
        .panel {
            background: rgba(255,255,255,0.96);
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 10px 30px rgba(11,20,34,0.08);
        }

        .filters .form-label { font-size: .9rem; }
        #noResults { display: none; }
        @media (max-width: 575.98px) {
            .filters .row > * { margin-bottom: .5rem; }
        }

        /* Alineaciones de tabla */
        .table td, .table th { vertical-align: middle; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="panel">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h3 class="mb-0">Notas de Venta</h3>
                <small class="text-muted">Revisa tus notas de venta</small>
            </div>
            <div>
                <a href="../panel.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
            </div>
        </div>

        <!-- FILTROS: Solo Fecha (única) y Total (flexible) -->
        <div class="card mb-3 filters">
            <div class="card-body">
                <form id="filtersForm" class="row g-2 align-items-end">
                    <div class="col-12 col-md-4">
                        <label class="form-label">Fecha (día)</label>
                        <input id="filterFecha" type="date" class="form-control form-control-sm">
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label">Total (buscar)</label>
                        <!-- placeholder vacío: no se muestran ejemplos -->
                        <input id="filterTotal" type="text" class="form-control form-control-sm" placeholder="">
                    </div>

                    <div class="col-12 col-md-4 d-flex gap-2">
                        <button id="btnApply" type="button" class="btn btn-sm btn-primary"><i class="bi bi-funnel"></i> Aplicar</button>
                        <button id="btnClear" type="button" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-circle"></i> Limpiar</button>
                        <div class="ms-auto"></div>
                    </div>
                </form>
            </div>
        </div>

        <div id="noResults" class="alert alert-warning">No se encontraron ventas con esos filtros.</div>

        <!-- TABLA -->
        <?php if (empty($ventas)): ?>
            <div class="alert alert-warning">No hay ventas registradas.</div>
        <?php else: ?>
        <div id="tableWrap" class="table-responsive">
            <table id="ventasTable" class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th class="text-end">Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($ventas as $venta):
                    $fecha_iso = date('Y-m-d', strtotime($venta['fecha'])); // formato para comparar (día)
                    $total_num = (float)$venta['total'];
                ?>
                    <tr data-fecha="<?= $fecha_iso ?>" data-total="<?= $total_num ?>">
                        <td><?= htmlspecialchars($venta['id']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($venta['fecha'])) ?></td>
                        <td><?= htmlspecialchars($venta['nombre_cliente']) ?></td>
                        <td class="text-end">$<?= number_format($total_num, 2) ?></td>
                        <td>
                            <a href="../Detalles_ventas/detalleVenta.php?id_venta=<?= urlencode($venta['id']) ?>" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i> Ver Detalles
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
(function(){
    const btnApply = document.getElementById('btnApply');
    const btnClear = document.getElementById('btnClear');
    const rows = Array.from(document.querySelectorAll('#ventasTable tbody tr'));
    const noResults = document.getElementById('noResults');

    const elFecha = document.getElementById('filterFecha'); // formato YYYY-MM-DD
    const elTotal = document.getElementById('filterTotal'); // entrada flexible

    function parseTotalQuery(q) {
        if (!q) return () => true;
        q = q.trim();
        // rango "min-max"
        const rangeMatch = q.match(/^(\d+(?:\.\d+)?)\s*-\s*(\d+(?:\.\d+)?)$/);
        if (rangeMatch) {
            const min = parseFloat(rangeMatch[1]);
            const max = parseFloat(rangeMatch[2]);
            return (val) => val >= Math.min(min,max) && val <= Math.max(min,max);
        }
        // operadores >=, <=, >, <
        const opMatch = q.match(/^(<=|>=|<|>)\s*(\d+(?:\.\d+)?)$/);
        if (opMatch) {
            const op = opMatch[1];
            const num = parseFloat(opMatch[2]);
            if (op === '>') return (v) => v > num;
            if (op === '>=') return (v) => v >= num;
            if (op === '<') return (v) => v < num;
            if (op === '<=') return (v) => v <= num;
        }
        // exacto "=50"
        const eqMatch = q.match(/^=\s*(\d+(?:\.\d+)?)$/);
        if (eqMatch) {
            const num = parseFloat(eqMatch[1]);
            return (v) => Math.abs(v - num) < 0.00001;
        }
        // plain number -> buscar como substring en la representación con 2 decimales
        const numPlain = q.match(/^(\d+(?:\.\d+)?)$/);
        if (numPlain) {
            const sub = numPlain[1];
            return (v) => v.toFixed(2).indexOf(sub) !== -1 || String(v).indexOf(sub) !== -1;
        }
        // por defecto: intentar comparar como número si se puede
        const tryNum = parseFloat(q);
        if (!isNaN(tryNum)) return (v) => v === tryNum;
        // si no es numérico, no filtrar por total
        return () => true;
    }

    function applyFilters() {
        const fecha = elFecha.value; // '' o 'YYYY-MM-DD'
        const totalQ = elTotal.value.trim();

        const totalPredicate = parseTotalQuery(totalQ);

        let visibleCount = 0;
        rows.forEach(r => {
            let ok = true;
            const rowFecha = r.dataset.fecha || ''; // YYYY-MM-DD
            const rowTotal = parseFloat(r.dataset.total || '0');

            if (fecha && rowFecha !== fecha) ok = false;
            if (totalQ && !totalPredicate(rowTotal)) ok = false;

            r.style.display = ok ? '' : 'none';
            if (ok) visibleCount++;
        });

        noResults.style.display = visibleCount === 0 ? '' : 'none';
    }

    btnApply.addEventListener('click', applyFilters);

    btnClear.addEventListener('click', function(){
        elFecha.value = '';
        elTotal.value = '';
        rows.forEach(r => r.style.display = '');
        noResults.style.display = 'none';
    });

    if (rows.length === 0) noResults.style.display = '';
    else noResults.style.display = 'none';
})();
</script>
</body>
</html>
