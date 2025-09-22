<?php
session_start();
include 'db.php';
if (!isset($_SESSION['id_usuario'])) {
    header('Location: inicio_sesion.php');
    exit;
}
$rol = $_SESSION['rol'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Panel de Control</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="contenedor">
        <h1>Bienvenido</h1>
        <nav>
            <?php if ($rol == 4) { // Cliente ?>
                <a href="Mascotas/listar.php">Gestionar Mascotas</a>
                <a href="Citas/crear.php">Agendar Citas</a>
                <a href="Historiales_Medicos/listar.php">Ver Historial Médico</a>
                <a href="Ventas/listar.php">Ver Notas de Venta</a>
            <?php } elseif ($rol == 2) { // Veterinario ?>
                <a href="disponibilidades_vet/crear.php">Gestionar Horarios</a> <!-- Extra para agenda -->
                <a href="Citas/listar.php">Ver Citas Asignadas</a>
                <a href="Historiales_Medicos/crear.php">Gestionar Historial Médico</a>
            <?php } elseif ($rol == 3) { // Recepcionista ?>
                <a href="Citas/listar.php">Gestionar Citas</a>
                <a href="Ventas/crear.php">Gestionar Ventas</a>
                <a href="Productos/listar.php">Gestionar Productos</a>
                <a href="Servicios/listar.php">Gestionar Servicios</a>
            <?php } elseif ($rol == 1) { // Administrador ?>
                <a href="Usuarios/listar.php">Gestionar Usuarios</a>
                <a href="reportes.php">Reportes</a>
                <!-- Acceso a todo -->
                <a href="Mascotas/listar.php">Mascotas</a>
                <a href="Citas/listar.php">Citas</a>
                <a href="Ventas/listar.php">Ventas</a>
                <a href="Historiales_Medicos/listar.php">Historial Médico</a>
                <a href="Productos/listar.php">Productos</a>
                <a href="Servicios/listar.php">Servicios</a>
            <?php } ?>
            <a href="cerrar_sesion.php">Cerrar Sesión</a>
        </nav>
    </div>
</body>
</html>