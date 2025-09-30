<?php
session_start();
include '../db.php';
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../inicio_sesion.php');
    exit;
}
$rol = $_SESSION['rol'];
$id_usuario = $_SESSION['id_usuario'];

// Obtener historiales (si cliente: solo sus mascotas)
if ($rol == 4) {
    $stmt = $pdo->prepare("
        SELECT h.*, m.nombre AS nombre_mascota 
        FROM historiales_medicos h 
        JOIN mascotas m ON h.id_mascota = m.id 
        WHERE m.id_dueno = ?
        ORDER BY h.fecha DESC
    ");
    $stmt->execute([$id_usuario]);
} else {
    $stmt = $pdo->query("
        SELECT h.*, m.nombre AS nombre_mascota 
        FROM historiales_medicos h 
        JOIN mascotas m ON h.id_mascota = m.id
        ORDER BY h.fecha DESC
    ");
}
$historiales = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Para los filtros: lista única de mascotas
$mascotas_unicas = [];
foreach ($historiales as $h) {
    $mascotas_unicas[$h['id_mascota']] = $h['nombre_mascota'];
}

// Tipos disponibles (fijo, ampliado, sin 'otro')
$tipos_disponibles = ['vacunacion','internacion','revision','desparasitacion','peluqueria'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historiales Médicos</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- CSS separado -->
    <link rel="stylesheet" href="../css/historiales.css">
</head>
<body>
  <main class="vh-100 d-flex align-items-start">
    <div class="container py-5">
      <div class="d-flex align-items-center mb-3">
        <h1 class="h3 mb-0 me-3">Historial Médico</h1>
        <small class="text-muted">Registros clínicos de tus mascotas</small>
      </div>

      <!-- FILTROS -->
      <div class="card mb-3 shadow-sm">
        <div class="card-body">
          <form id="filtersForm" class="row g-2 align-items-end">
            <div class="col-12 col-md-4">
              <label class="form-label small">Mascota</label>
              <select id="filterMascota" class="form-select form-select-sm">
                <option value="">— Todas —</option>
                <?php foreach ($mascotas_unicas as $mid => $mname): ?>
                  <option value="<?= htmlspecialchars($mid) ?>"><?= htmlspecialchars($mname) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-6 col-md-3">
              <label class="form-label small">Tipo</label>
              <select id="filterTipo" class="form-select form-select-sm">
                <option value="">— Todos —</option>
                <?php foreach ($tipos_disponibles as $tipo): ?>
                  <option value="<?= htmlspecialchars($tipo) ?>"><?= htmlspecialchars(ucfirst($tipo)) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-6 col-md-5">
              <label class="form-label small">Buscar (diagnóstico / tratamiento)</label>
              <input id="filterSearch" type="search" class="form-control form-control-sm" placeholder="texto libre">
            </div>

            <!-- Único filtro de fecha -->
            <div class="col-6 col-md-2">
              <label class="form-label small">Fecha</label>
              <input id="filterDate" type="date" class="form-control form-control-sm">
            </div>

            <div class="col-12 col-md-4 d-flex gap-2">
              <button id="btnApply" type="button" class="btn btn-sm btn-primary mt-1"><i class="bi bi-funnel"></i> Aplicar</button>
              <button id="btnClear" type="button" class="btn btn-sm btn-outline-secondary mt-1"><i class="bi bi-x-circle"></i> Limpiar</button>
              <button id="btnExportPdf" type="button" class="btn btn-sm btn-success ms-auto mt-1"><i class="bi bi-file-earmark-pdf"></i> Exportar PDF</button>
            </div>
          </form>
        </div>
      </div>

      <!-- TABLA -->
      <?php if (count($historiales) === 0): ?>
        <div class="alert alert-warning">No hay historiales registrados.</div>
      <?php else: ?>
      <div id="tableWrap" class="table-responsive shadow-sm rounded bg-white p-3">
        <table id="histTable" class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Mascota</th>
              <th>Fecha</th>
              <th>Tipo</th>
              <th>Diagnóstico</th>
              <th>Tratamiento</th>
              <th>Vacunas</th>
              <th>Evolución</th>
              <?php if ($rol != 4): ?><th class="text-end">Acciones</th><?php endif; ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($historiales as $hist):
                $fecha_iso = date('Y-m-d', strtotime($hist['fecha']));
            ?>
              <tr data-mascota="<?= htmlspecialchars($hist['id_mascota']) ?>"
                  data-tipo="<?= htmlspecialchars($hist['tipo']) ?>"
                  data-fecha="<?= $fecha_iso ?>">
                <td data-label="#"> <?= htmlspecialchars($hist['id']) ?> </td>
                <td data-label="Mascota"><?= htmlspecialchars($hist['nombre_mascota']) ?></td>
                <td data-label="Fecha"><?= date('d/m/Y H:i', strtotime($hist['fecha'])) ?></td>
                <td data-label="Tipo">
                  <?php
                    $tipo = $hist['tipo'] ?? '';
                    $badgeClass = 'badge bg-dark';
                    if ($tipo === 'vacunacion') $badgeClass = 'badge bg-success';
                    elseif ($tipo === 'internacion') $badgeClass = 'badge bg-warning text-dark';
                    elseif ($tipo === 'revision') $badgeClass = 'badge bg-primary';
                    elseif ($tipo === 'desparasitacion') $badgeClass = 'badge bg-info text-dark';
                    elseif ($tipo === 'peluqueria') $badgeClass = 'badge bg-secondary';
                  ?>
                  <span class="<?= $badgeClass ?>"><?= htmlspecialchars(ucfirst($tipo)) ?></span>
                </td>
                <td data-label="Diagnóstico"><?= nl2br(htmlspecialchars($hist['diagnostico'])) ?></td>
                <td data-label="Tratamiento"><?= nl2br(htmlspecialchars($hist['tratamiento'])) ?></td>
                <td data-label="Vacunas"><?= nl2br(htmlspecialchars($hist['vacunas'])) ?></td>
                <td data-label="Evolución"><?= nl2br(htmlspecialchars($hist['evolucion'])) ?></td>
                <?php if ($rol != 4): ?>
                <td class="text-end" data-label="Acciones">
                  <a href="editar.php?id=<?= urlencode($hist['id']) ?>" class="btn btn-sm btn-outline-primary me-1" title="Editar"><i class="bi bi-pencil"></i></a>
                  <a href="eliminar.php?id=<?= urlencode($hist['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar este historial?')"><i class="bi bi-trash"></i></a>
                </td>
                <?php endif; ?>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php endif; ?>

      <div class="mt-4 d-flex justify-content-between align-items-center">
        <a href="../panel.php" class="btn btn-light btn-outline-secondary"><i class="bi bi-arrow-left"></i> Volver al panel</a>
        <small class="text-muted">Última actualización: <?= date('d/m/Y H:i') ?></small>
      </div>
    </div>
  </main>

  <!-- Librerías para exportar PDF -->
  <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>

  <script>
    // FILTRADO cliente-side con UNICA fecha
    (function(){
      const btnApply = document.getElementById('btnApply');
      const btnClear = document.getElementById('btnClear');
      const selectMasc = document.getElementById('filterMascota');
      const selectTipo = document.getElementById('filterTipo');
      const inputDate = document.getElementById('filterDate');
      const inputSearch = document.getElementById('filterSearch');
      const rows = Array.from(document.querySelectorAll('#histTable tbody tr'));

      function applyFilters() {
        const masc = selectMasc.value;
        const tipo = selectTipo.value.toLowerCase();
        const date = inputDate.value;
        const q = inputSearch.value.trim().toLowerCase();

        rows.forEach(r => {
          let ok = true;
          if (masc && r.dataset.mascota !== masc) ok = false;
          if (tipo && (r.dataset.tipo || '').toLowerCase() !== tipo) ok = false;
          const fecha = r.dataset.fecha || '';
          if (date && fecha !== date) ok = false;
          if (q) {
            const diag = (r.querySelector('td[data-label="Diagnóstico"]')?.innerText || '').toLowerCase();
            const trata = (r.querySelector('td[data-label="Tratamiento"]')?.innerText || '').toLowerCase();
            const mascName = (r.querySelector('td[data-label="Mascota"]')?.innerText || '').toLowerCase();
            if (!(diag.includes(q) || trata.includes(q) || mascName.includes(q))) ok = false;
          }
          r.style.display = ok ? '' : 'none';
        });
      }

      btnApply.addEventListener('click', applyFilters);
      btnClear.addEventListener('click', function(){
        selectMasc.value = '';
        selectTipo.value = '';
        inputDate.value = '';
        inputSearch.value = '';
        rows.forEach(r => r.style.display = '');
      });

      inputSearch.addEventListener('keydown', function(e){ if (e.key === 'Enter') { e.preventDefault(); applyFilters(); } });
    })();

    // EXPORTAR A PDF
    (function(){
      const { jsPDF } = window.jspdf;
      const btnPdf = document.getElementById('btnExportPdf');
      const tableWrap = document.getElementById('tableWrap');

      btnPdf.addEventListener('click', async function(){
        const originalButtons = Array.from(document.querySelectorAll('#filtersForm button'));
        originalButtons.forEach(b => b.style.visibility = 'hidden');

        const clone = tableWrap.cloneNode(true);
        clone.querySelectorAll('a, button').forEach(n => n.remove());
        clone.style.width = '1100px';
        clone.style.background = '#fff';
        document.body.appendChild(clone);

        try {
          const canvas = await html2canvas(clone, { scale: 2, useCORS: true, backgroundColor: '#ffffff' });
          const imgData = canvas.toDataURL('image/jpeg', 0.95);
          const pdf = new jsPDF('l', 'mm', 'a4');
          const pageWidth = pdf.internal.pageSize.getWidth();
          const pageHeight = pdf.internal.pageSize.getHeight();
          const imgProps = pdf.getImageProperties(imgData);
          const imgWidthMm = pageWidth - 16;
          const imgHeightMm = (imgProps.height * imgWidthMm) / imgProps.width;

          if (imgHeightMm <= pageHeight - 16) {
            pdf.addImage(imgData, 'JPEG', 8, 8, imgWidthMm, imgHeightMm);
          } else {
            let remainingHeight = canvas.height;
            const sliceHeight = Math.floor(canvas.width * ((pageHeight - 16) / imgWidthMm));
            let offsetY = 0;
            let first = true;
            while (remainingHeight > 0) {
              const sliceCanvas = document.createElement('canvas');
              sliceCanvas.width = canvas.width;
              sliceCanvas.height = Math.min(sliceHeight, remainingHeight);
              const ctx = sliceCanvas.getContext('2d');
              ctx.drawImage(canvas, 0, offsetY, canvas.width, sliceCanvas.height, 0, 0, sliceCanvas.width, sliceCanvas.height);
              const sliceData = sliceCanvas.toDataURL('image/jpeg', 0.95);
              if (!first) pdf.addPage();
              pdf.addImage(sliceData, 'JPEG', 8, 8, imgWidthMm, (sliceCanvas.height * imgWidthMm) / sliceCanvas.width);
              remainingHeight -= sliceCanvas.height;
              offsetY += sliceCanvas.height;
              first = false;
            }
          }

          const filename = 'historiales_' + (new Date()).toISOString().slice(0,19).replace(/[:T]/g,'-') + '.pdf';
          pdf.save(filename);
        } catch (err) {
          console.error('Error generando PDF', err);
          alert('Ocurrió un error al generar el PDF. Revisa la consola.');
        } finally {
          originalButtons.forEach(b => b.style.visibility = 'visible');
          clone.remove();
        }
      });
    })();
  </script>
</body>
</html>
