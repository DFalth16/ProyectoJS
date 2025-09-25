<?php
// panel_control.php (mejorado visualmente con Bootstrap)
session_start();
require 'db.php'; // tu conexión PDO

if (!isset($_SESSION['id_usuario'])) {
    header('Location: index.php');
    exit;
}

$rol = (int)($_SESSION['rol'] ?? 0);
$nombre = htmlspecialchars($_SESSION['nombre'] ?? $_SESSION['nombre_usuario'] ?? '', ENT_QUOTES, 'UTF-8');

function roleNameFromSession() {
    return $_SESSION['rol_nombre'] ?? ([
        1 => 'Administrador',
        2 => 'Veterinario',
        3 => 'Recepcionista',
        4 => 'Cliente'
    ][ $_SESSION['rol'] ?? 0 ] ?? 'Desconocido');
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Panel de Control - Vet</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body { background: #f5f7fb; }
    .brand { font-weight:700; letter-spacing: .2px; }
    .role-badge { font-size:0.85rem; }
    .card-action { transition: transform .12s ease, box-shadow .12s ease; cursor: pointer; }
    .card-action:hover { transform: translateY(-6px); box-shadow: 0 10px 30px rgba(15,23,42,0.12); }
    .welcome { font-size:1.05rem; }
    .nav-quick a { text-decoration: none; color: #495057; }
    .nav-quick a:hover { text-decoration: none; color: #0b74da; }
    footer { font-size:0.85rem; color:#6c757d; padding:18px 0; }

    /* ===== Fondo para la sección cliente ===== */
    .client-section{
      position: relative;
      overflow: hidden;
      background-image: url('img/imagen1.png'); /* <-- ruta actualizada a tu imagen */
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      padding: 28px;
      border-radius: 12px;
      color: #fff;
      min-height: 320px;
    }

    /* overlay para asegurar legibilidad */
    .client-section::before {
      content: "";
      position: absolute;
      inset: 0;
      background: rgba(0,0,0,0.35); /* oscurece la imagen; cambia opacidad si quieres */
      z-index: 0;
      border-radius: 12px;
    }

    /* contenido sobre el overlay */
    .client-section .client-content {
      position: relative;
      z-index: 1;
    }

    /* Opcional: hacer que las cards mantengan fondo blanco y sobresalgan sobre la imagen */
    .client-section .card {
      background-clip: padding-box; /* para que la sombra y el borde se muestren bien */
    }

    @media (max-width: 575.98px) {
      .client-section { padding: 18px; min-height: 260px; }
    }
  </style>
</head>
<body>

  <!-- Topbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2 brand" href="#">
        <i class="bi bi-heart-pulse-fill text-primary" style="font-size:1.25rem;"></i>
        <span>Vet <small class="text-muted d-block" style="font-size:0.72rem;">Centro Veterinario</small></span>
      </a>

      <div class="d-flex align-items-center ms-auto gap-3">
        <div class="text-end me-3">
          <div class="welcome">Hola, <strong><?= $nombre ?></strong></div>
          <div class="text-muted" style="font-size:0.85rem;"><?= htmlspecialchars(roleNameFromSession(), ENT_QUOTES) ?></div>
        </div>
        <a href="logout.php" class="btn btn-outline-secondary">Cerrar sesión</a>
      </div>
    </div>
  </nav>

  <!-- Main content -->
  <main class="container my-4">
    <div class="row align-items-center mb-3">
      <div class="col-md-8">
        <h2 class="mb-0">Panel de Control</h2>
        <p class="text-muted mb-0">Accesos rápidos y gestión según tu rol</p>
      </div>
      <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <span class="badge bg-info text-dark role-badge">Rol: <?= htmlspecialchars(roleNameFromSession(), ENT_QUOTES) ?></span>
      </div>
    </div>

    <!-- CLIENT VIEW -->
    <?php if ($rol === 4): ?>
      <section class="mb-4 client-section">
        <div class="client-content">
          <h4 class="mb-3 text-white">Accesos rápidos</h4>

          <div class="row g-3">
            <div class="col-12 col-sm-6 col-md-4">
              <a href="Mascotas/listar.php" class="text-decoration-none">
                <div class="card card-action h-100">
                  <div class="card-body d-flex align-items-start gap-3">
                    <div class="bg-primary text-white rounded-3 p-2">
                      <i class="bi bi-paw fs-4"></i>
                    </div>
                    <div>
                      <h6 class="mb-1">Gestionar Mascotas</h6>
                      <p class="mb-0 text-muted small">Añade, edita o elimina tus mascotas registradas.</p>
                    </div>
                  </div>
                </div>
              </a>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
              <a href="Citas/crear.php" class="text-decoration-none">
                <div class="card card-action h-100">
                  <div class="card-body d-flex align-items-start gap-3">
                    <div class="bg-success text-white rounded-3 p-2">
                      <i class="bi bi-calendar-plus fs-4"></i>
                    </div>
                    <div>
                      <h6 class="mb-1">Agendar Cita</h6>
                      <p class="mb-0 text-muted small">Solicita una consulta rápida con nuestros veterinarios.</p>
                    </div>
                  </div>
                </div>
              </a>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
              <a href="Historiales_Medicos/listar.php" class="text-decoration-none">
                <div class="card card-action h-100">
                  <div class="card-body d-flex align-items-start gap-3">
                    <div class="bg-warning text-dark rounded-3 p-2">
                      <i class="bi bi-journal-medical fs-4"></i>
                    </div>
                    <div>
                      <h6 class="mb-1">Historial Médico</h6>
                      <p class="mb-0 text-muted small">Consulta vacunas, tratamientos y evolución.</p>
                    </div>
                  </div>
                </div>
              </a>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
              <a href="Ventas/listar.php" class="text-decoration-none">
                <div class="card card-action h-100">
                  <div class="card-body d-flex align-items-start gap-3">
                    <div class="bg-secondary text-white rounded-3 p-2">
                      <i class="bi bi-receipt-cutoff fs-4"></i>
                    </div>
                    <div>
                      <h6 class="mb-1">Notas de Venta</h6>
                      <p class="mb-0 text-muted small">Revisa compras y recibos de servicios/productos.</p>
                    </div>
                  </div>
                </div>
              </a>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
              <a href="Perfil/editar.php" class="text-decoration-none">
                <div class="card card-action h-100">
                  <div class="card-body d-flex align-items-start gap-3">
                    <div class="bg-info text-white rounded-3 p-2">
                      <i class="bi bi-person-gear fs-4"></i>
                    </div>
                    <div>
                      <h6 class="mb-1">Mi Perfil</h6>
                      <p class="mb-0 text-muted small">Edita tus datos de contacto y contraseña.</p>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          </div> <!-- /.row -->

          <!-- Información adicional / ayuda -->
          <div class="mt-4">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">¿Necesitas ayuda?</h5>
                <p class="card-text text-muted">Si tienes problemas para agendar o acceder a tus datos, comunícate con la recepción o envía un correo a <a href="mailto:soporte@vet.com">soporte@vet.com</a>.</p>
                <div class="d-flex gap-2">
                  <a href="Citas/crear.php" class="btn btn-primary">Agendar cita</a>
                  <a href="contacto.php" class="btn btn-outline-secondary">Contactar soporte</a>
                </div>
              </div>
            </div>
          </div>
        </div> <!-- /.client-content -->
      </section>
    <?php endif; ?>

    <!-- VIEWS para otros roles (con estilo compacto) -->
    <?php if ($rol === 2): // Veterinario ?>
      <section>
        <h4>Panel Veterinario</h4>
        <div class="list-group">
          <a href="Citas/listar.php" class="list-group-item list-group-item-action">Ver Citas</a>
          <a href="Historiales_Medicos/crear.php" class="list-group-item list-group-item-action">Registrar Historial Médico</a>
        </div>
      </section>
    <?php endif; ?>

    <?php if ($rol === 3): // Recepcionista ?>
      <section>
        <h4>Panel Recepción</h4>
        <div class="list-group">
          <a href="Citas/listar.php" class="list-group-item list-group-item-action">Gestionar Citas</a>
          <a href="Ventas/crear.php" class="list-group-item list-group-item-action">Registrar Venta</a>
          <a href="Productos/listar.php" class="list-group-item list-group-item-action">Productos</a>
          <a href="Servicios/listar.php" class="list-group-item list-group-item-action">Servicios</a>
        </div>
      </section>
    <?php endif; ?>

    <?php if ($rol === 1): // Administrador ?>
      <section>
        <h4>Panel Administrador</h4>
        <div class="row g-3">
          <div class="col-md-4">
            <a href="Usuarios/listar.php" class="text-decoration-none">
              <div class="card card-action">
                <div class="card-body d-flex gap-3 align-items-start">
                  <div class="bg-primary text-white rounded-3 p-2"><i class="bi bi-people fs-4"></i></div>
                  <div>
                    <h6 class="mb-1">Gestionar Usuarios</h6>
                    <p class="mb-0 text-muted small">Crear, editar y eliminar cuentas.</p>
                  </div>
                </div>
              </div>
            </a>
          </div>
          <div class="col-md-4">
            <a href="reportes.php" class="text-decoration-none">
              <div class="card card-action">
                <div class="card-body d-flex gap-3 align-items-start">
                  <div class="bg-success text-white rounded-3 p-2"><i class="bi bi-bar-chart-line fs-4"></i></div>
                  <div>
                    <h6 class="mb-1">Reportes</h6>
                    <p class="mb-0 text-muted small">Ventas, citas y estadísticas.</p>
                  </div>
                </div>
              </div>
            </a>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <footer class="mt-5">
      <div class="text-center text-muted">Sistema Vet — &copy; <?= date('Y') ?>. <br class="d-sm-none">Diseñado para administración de clínicas veterinarias.</div>
    </footer>
  </main>

  <!-- Bootstrap 5 JS (Popper incluido) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
