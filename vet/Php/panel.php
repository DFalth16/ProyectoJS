<?php
// panel.php - Panel de Control con vistas por rol
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
  <title>Panel de Control - Amigos de Cuatro Patas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Tu CSS externo -->
  <link rel="stylesheet" href="Css/panel_control.css">
</head>
<body>

  <!-- Topbar -->
  <nav class="navbar navbar-expand-lg navbar-light navbar-custom shadow-sm">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2 brand" href="index.php">
        <img src="img/Logo.jpg" alt="Amigos de Cuatro Patas - Logo" class="brand-logo" />
        <span class="ms-2">Amigos de Cuatro Patas <small class="d-block brand-sub">Centro Veterinario</small></span>
      </a>

      <div class="d-flex align-items-center ms-auto gap-3">
        <div class="text-end me-3 d-none d-md-block">
          <div class="welcome">Hola, <strong><?= $nombre ?></strong></div>
          <div class="text-white role-text"><?= htmlspecialchars(roleNameFromSession(), ENT_QUOTES) ?></div>
        </div>
        <a href="logout.php" class="btn btn-outline-secondary">Cerrar sesión</a>
      </div>
    </div>
  </nav>

  <!-- Main -->
  <main class="container page-container position-relative" role="main">
    <div class="role-floating" aria-hidden="true">
      <span class="role-pill">Rol: <?= htmlspecialchars(roleNameFromSession(), ENT_QUOTES) ?></span>
    </div>

    <div class="row align-items-center mb-3">
      <div class="col-md-8">
        <h2 class="mb-0">Panel de Control</h2>
        <p class="text-muted mb-0">Accesos rápidos y gestión según tu rol</p>
      </div>
      <div class="col-md-4 text-md-end mt-3 mt-md-0 d-block d-md-none">
        <span class="role-pill">Rol: <?= htmlspecialchars(roleNameFromSession(), ENT_QUOTES) ?></span>
      </div>
    </div>

    <!-- CLIENTE -->
    <?php if ($rol === 4): ?>
      <section class="mb-4" aria-labelledby="accesos-titulo">
        <div class="client-wrapper">
          <h4 id="accesos-titulo" class="mb-3">Accesos rápidos</h4>
          <div class="row g-3 align-items-stretch">
            <div class="col-12 col-sm-6 col-md-4 d-flex">
              <a href="Mascotas/vermis_mascotas.php" class="text-decoration-none w-100">
                <div class="card card-action h-100">
                  <div class="card-body d-flex align-items-start gap-3">
                    <i class="bi bi-paw fs-4"></i>
                    <div>
                      <h6 class="mb-1">Gestionar Mascotas</h6>
                      <p class="mb-0 small">Añade, edita o elimina tus mascotas registradas.</p>
                    </div>
                  </div>
                </div>
              </a>
            </div>

            <div class="col-12 col-sm-6 col-md-4 d-flex">
             <a href="Citas/mis_citas.php" class="text-decoration-none w-100">
                <div class="card card-action h-100">
                  <div class="card-body d-flex align-items-start gap-3">
                    <i class="bi bi-calendar-plus fs-4"></i>
                    <div>
                     <h6 class="mb-1">Mis Citas</h6>
                     <p class="mb-0 small">Ver todas las citas de tus mascotas.</p>
                    </div>
                  </div>
                </div>
              </a>
            </div>


            <div class="col-12 col-sm-6 col-md-4 d-flex">
              <a href="Historiales_Medicos/HistorialMedico.php" class="text-decoration-none w-100">
                <div class="card card-action h-100">
                  <div class="card-body d-flex align-items-start gap-3">
                    <i class="bi bi-journal-medical fs-4"></i>
                    <div>
                      <h6 class="mb-1">Historial Médico</h6>
                      <p class="mb-0 small">Consulta vacunas, tratamientos y evolución.</p>
                    </div>
                  </div>
                </div>
              </a>
            </div>

            
            <div class="col-12 col-sm-6 col-md-4 d-flex">
              <a href="Ventas/NotasVentas.php" class="text-decoration-none w-100">
                <div class="card card-action h-100">
                  <div class="card-body d-flex align-items-start gap-3">
                    <i class="bi bi-receipt-cutoff fs-4"></i>
                    <div>
                      <h6 class="mb-1">Notas de Venta</h6>
                      <p class="mb-0 small">Revisa compras y recibos de servicios/productos.</p>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          </div>

 
        </div>
      </section>
    <?php endif; ?>

    <!-- VETERINARIO -->
    <?php if ($rol === 2): ?>
      <section>
        <h4>Panel Veterinario</h4>
        <div class="list-group">
          <a href="Citas/listar.php" class="list-group-item list-group-item-action">Ver Citas</a>
          <a href="Historiales_Medicos/crear.php" class="list-group-item list-group-item-action">Registrar Historial Médico</a>
        </div>
      </section>
    <?php endif; ?>

    <!-- RECEPCIONISTA -->
    <?php if ($rol === 3): ?>
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

<!-- ADMINISTRADOR -->
<?php if ($rol === 1): ?>
  <section>
    <h4>Panel Administrador</h4>
    <div class="row g-3">

      <div class="col-md-4">
        <a href="Usuarios/listar.php" class="text-decoration-none">
          <div class="card card-action">
            <div class="card-body d-flex gap-3 align-items-start">
              <i class="bi bi-people fs-4"></i>
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
              <i class="bi bi-bar-chart-line fs-4"></i>
              <div>
                <h6 class="mb-1">Reportes</h6>
                <p class="mb-0 text-muted small">Ventas, citas y estadísticas.</p>
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-4">
        <a href="Mascotas/listar.php" class="text-decoration-none">
          <div class="card card-action">
            <div class="card-body d-flex gap-3 align-items-start">
              <i class="bi bi-paw fs-4"></i>
              <div>
                <h6 class="mb-1">Mascotas</h6>
                <p class="mb-0 text-muted small">Ver y gestionar todas las mascotas registradas.</p>
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-4">
        <a href="Citas/listar.php" class="text-decoration-none">
          <div class="card card-action">
            <div class="card-body d-flex gap-3 align-items-start">
              <i class="bi bi-calendar fs-4"></i>
              <div>
                <h6 class="mb-1">Citas</h6>
                <p class="mb-0 text-muted small">Gestionar todas las citas programadas.</p>
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-4">
        <a href="Ventas/listar.php" class="text-decoration-none">
          <div class="card card-action">
            <div class="card-body d-flex gap-3 align-items-start">
              <i class="bi bi-receipt-cutoff fs-4"></i>
              <div>
                <h6 class="mb-1">Ventas</h6>
                <p class="mb-0 text-muted small">Revisar y administrar todas las ventas.</p>
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-4">
        <a href="Historiales_Medicos/listar.php" class="text-decoration-none">
          <div class="card card-action">
            <div class="card-body d-flex gap-3 align-items-start">
              <i class="bi bi-journal-medical fs-4"></i>
              <div>
                <h6 class="mb-1">Historial Médico</h6>
                <p class="mb-0 text-muted small">Consultar todos los historiales médicos registrados.</p>
              </div>
            </div>
          </div>
        </a>
      </div>

    </div>
  </section>
<?php endif; ?>


    <footer class="mt-5">
      <div class="text-center text-muted">
        Sistema Vet — &copy; <?= date('Y') ?>. <br class="d-sm-none">
        Diseñado para administración de clínicas veterinarias.
      </div>
    </footer>
  </main>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
