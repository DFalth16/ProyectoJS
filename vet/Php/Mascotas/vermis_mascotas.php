<?php
session_start();
include '../db.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../index.php');
    exit;
}

$rol = (int)($_SESSION['rol'] ?? 0);
$id_dueno = (int)($_SESSION['id_usuario']);

if ($rol === 4) {
    $stmt = $pdo->prepare("SELECT * FROM mascotas WHERE id_dueno = ?");
    $stmt->execute([$id_dueno]);
} else {
    $stmt = $pdo->query("SELECT * FROM mascotas");
}
$mascotas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Mis Mascotas — Amigos de Cuatro Patas</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/mascotas.css">

  <style>
    /* Fondo global neón difuminado + imagen */
    body {
      background: radial-gradient(circle at top left, rgba(0,229,255,0.2), rgba(0,229,255,0) 70%),
                  radial-gradient(circle at bottom right, rgba(255,105,180,0.15), rgba(255,105,180,0) 70%),
                  url('../img/imagen1.png') center / cover no-repeat fixed;
      min-height: 100vh;
      font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      color: #111;
    }

    /* Modal detalles */
    #modalImgWrapper img { max-height: 320px; width: auto; object-fit: cover; border-radius: 8px; }
    #modalList { margin:0; padding:0; list-style:none; }
    #modalList li { padding: 8px 0; border-bottom: 1px dashed rgba(0,0,0,0.04); }
    #modalList li:last-child { border-bottom: none; }
    #modalList li strong { color: #0b7285; margin-right:6px; }

    /* Botón volver */
    .back-btn { min-width: 88px; }

    /* Cards semi-translúcidas con glow neón */
    .pet-card {
      border-radius: 12px;
      background: rgba(255,255,255,0.85);
      box-shadow: 0 0 20px rgba(0,229,255,0.2), 0 12px 30px rgba(11,20,34,0.06);
      display: flex;
      flex-direction: column;
      transition: transform .2s ease, box-shadow .2s ease;
      border: 1px solid rgba(11,20,34,0.03);
    }
    .pet-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 0 30px rgba(0,229,255,0.3), 0 24px 60px rgba(11,20,34,0.1);
    }

    /* Badges con efecto neón */
    .card-badge {
      position: absolute;
      left: 10px;
      top: 10px;
      background: linear-gradient(90deg, #00e5ff, #00c7d9);
      color: #001f21;
      padding: 6px 10px;
      border-radius: 999px;
      font-weight: 700;
      font-size: .78rem;
      text-shadow: 0 0 4px rgba(0,229,255,0.7);
    }

    /* Botón “+ Registrar Mascota” neón */
    .btn-neon {
      background: linear-gradient(90deg, #00e5ff, #00c7d9);
      color: #001f21;
      border: none;
      box-shadow: 0 0 8px rgba(0,229,255,0.6), 0 8px 22px rgba(0,229,255,0.08);
      padding: .45rem .6rem;
      border-radius: 8px;
      font-weight: 700;
    }
    .btn-neon:hover, .btn-neon:focus {
      background: linear-gradient(90deg, #00c7d9, #00bcd4);
      color: #001f21;
      box-shadow: 0 0 12px rgba(0,229,255,0.8), 0 12px 30px rgba(0,229,255,0.1);
    }
  </style>
</head>
<body>
  <div class="container py-4">
    <div class="d-flex align-items-start gap-3 mb-3">
      <div class="me-2">
        <h1 class="h3 mb-1">Mis Mascotas</h1>
        <p class="text-muted mb-0">Consulta la información de tus mascotas.</p>
      </div>

      <div class="ms-auto d-flex flex-column align-items-end gap-2">
        <div class="d-flex align-items-center gap-2">
          <a href="../panel.php" class="btn btn-sm btn-outline-secondary back-btn" onclick="history.back(); return false;">
            <i class="bi bi-arrow-left me-1"></i> Volver
          </a>
          <div style="min-width:260px;">
            <input id="searchInput" class="form-control form-control-sm" placeholder="Buscar por nombre, especie o raza">
          </div>
        </div>
        <?php if ($rol !== 4): ?>
          <div>
            <a href="crear.php" class="btn btn-sm btn-neon">+ Registrar Mascota</a>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <?php if (empty($mascotas)): ?>
      <div class="alert alert-warning">No hay mascotas registradas.</div>
    <?php else: ?>
      <div id="petsGrid" class="row g-3">
        <?php foreach ($mascotas as $masc):
          $foto = $masc['foto'] ?? '';
          $ruta = '../uploads/' . $foto;
          $hasFoto = !empty($foto) && file_exists($ruta);
          $dataSearch = strtolower(($masc['nombre'] ?? '') . ' ' . ($masc['especie'] ?? '') . ' ' . ($masc['raza'] ?? ''));

          $public = $masc;
          unset($public['id_dueno'], $public['owner_id'], $public['id_owner']);
          $json_b64 = base64_encode(json_encode($public, JSON_UNESCAPED_UNICODE));
        ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 pet-card-col" data-search="<?= htmlspecialchars($dataSearch, ENT_QUOTES, 'UTF-8') ?>">
          <article class="card pet-card h-100">
            <div class="card-media">
              <?php if ($hasFoto): ?>
                <img src="<?= '../uploads/' . rawurlencode($foto) ?>" alt="<?= htmlspecialchars('Foto de ' . ($masc['nombre'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="card-img">
              <?php else: ?>
                <div class="card-img-placeholder"><i class="bi bi-image"></i></div>
              <?php endif; ?>
              <div class="card-badge"><?= htmlspecialchars($masc['especie'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
            </div>

            <div class="card-body d-flex flex-column">
              <h5 class="card-title mb-1"><?= htmlspecialchars($masc['nombre'] ?? '', ENT_QUOTES, 'UTF-8') ?></h5>
              <p class="text-muted small mb-2"><strong>Raza:</strong> <?= htmlspecialchars($masc['raza'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>

              <div class="mt-auto d-flex gap-2">
                <button class="btn btn-outline-dark btn-sm w-100 view-btn"
                        data-json="<?= htmlspecialchars($json_b64, ENT_QUOTES, 'UTF-8') ?>">
                  <i class="bi bi-eye-fill me-1"></i> Ver
                </button>
                <?php if ($rol !== 4): ?>
                  <a href="editar.php?id=<?= urlencode($masc['id']) ?>" class="btn btn-primary btn-sm w-100"><i class="bi bi-pencil-fill me-1"></i> Editar</a>
                <?php endif; ?>
              </div>
              <?php if ($rol !== 4): ?>
                <div class="mt-2 d-flex gap-2">
                  <a href="eliminar.php?id=<?= urlencode($masc['id']) ?>" class="btn btn-sm btn-danger w-100" onclick="return confirm('¿Eliminar esta mascota?')"><i class="bi bi-trash-fill"></i> Eliminar</a>
                </div>
              <?php endif; ?>
            </div>
          </article>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="petModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title">Detalles</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-5 text-center"><div id="modalImgWrapper" class="mb-3"></div></div>
            <div class="col-md-7">
              <h5 id="modalName" class="mb-1"></h5>
              <p id="modalSpecs" class="text-muted small mb-3"></p>
              <ul id="modalList" class="list-unstyled small text-start"></ul>
            </div>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function b64DecodeUnicode(str) {
      try { return decodeURIComponent(Array.prototype.map.call(atob(str), c => '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2)).join('')); }
      catch(e){ return atob(str); }
    }

    // FILTRADO
    (function(){
      const input = document.getElementById('searchInput');
      const cards = Array.from(document.querySelectorAll('.pet-card-col'));
      if (!input) return;
      input.addEventListener('input', e => {
        const q = e.target.value.trim().toLowerCase();
        cards.forEach(col => {
          const ts = col.getAttribute('data-search') || '';
          col.style.display = ts.includes(q) ? '' : 'none';
        });
      });
    })();

    // MODAL
    (function(){
      const petsGrid = document.getElementById('petsGrid');
      const petModalEl = document.getElementById('petModal');
      const bsModal = new bootstrap.Modal(petModalEl);
      const modalImgWrapper = document.getElementById('modalImgWrapper');
      const modalName = document.getElementById('modalName');
      const modalSpecs = document.getElementById('modalSpecs');
      const modalList = document.getElementById('modalList');

      petsGrid?.addEventListener('click', evt => {
        const viewBtn = evt.target.closest?.('.view-btn');
        if (viewBtn) { openModalFromButton(viewBtn); return; }
        const card = evt.target.closest?.('.pet-card');
        if (card && !evt.target.closest('a') && !evt.target.closest('button')) {
          const btn = card.querySelector('.view-btn');
          if (btn) openModalFromButton(btn);
        }
      });

      function openModalFromButton(viewBtn) {
        const jsonB64 = viewBtn.getAttribute('data-json') || '';
        if (!jsonB64) return;
        let obj;
        try { obj = JSON.parse(b64DecodeUnicode(jsonB64)); } catch(err){ console.error(err); return; }

        const nombre = obj.nombre || obj.name || '';
        const especie = obj.especie || obj.species || '';
        const raza = obj.raza || obj.breed || '';

        modalName.textContent = nombre || '—';
        modalSpecs.textContent = ((especie ? especie : '') + (especie && raza ? ' · ' : '') + (raza ? raza : '')).trim();

        modalList.innerHTML = '';
        const exclude = ['id','foto','nombre','name','especie','species','raza','breed','id_dueno','owner_id','id_owner'];
        Object.keys(obj).forEach(k => {
          if (exclude.includes(k)) return;
          const v = obj[k];
          if (!v) return;
          const label = k.replace(/_/g,' ').replace(/\b\w/g,c=>c.toUpperCase());
          const li = document.createElement('li');
          li.innerHTML = '<strong>' + escapeHtml(label) + ':</strong> ' + escapeHtml(String(v));
          modalList.appendChild(li);
        });

        modalImgWrapper.innerHTML = '';
        if (obj.foto) {
          const img = document.createElement('img');
          img.src = '../uploads/' + encodeURI(obj.foto);
          img.alt = 'Foto de ' + (nombre || 'mascota');
          img.className = 'img-fluid rounded';
          modalImgWrapper.appendChild(img);
        } else {
          modalImgWrapper.innerHTML = '<div class="text-muted">Sin foto disponible</div>';
        }

        bsModal.show();
      }

      function escapeHtml(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');
      }
    })();
  </script>
</body>
</html>
