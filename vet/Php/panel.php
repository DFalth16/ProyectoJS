<?php
include 'db.php';
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: index.php');
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
    <div class="container">
        <h1>Bienvenido</h1>
        <nav>
            <?php if ($rol == 4) { // Cliente ?>
                <a href="Mascotas/listar.php">Gestionar Mascotas</a>
                <a href="Citas/crear.php">Agendar Citas</a>
                <a href="Historiales_Medicos/listar.php">Ver Historial</a>
                <a href="Ventas/listar.php">Ver Notas de Venta</a>
            <?php } elseif ($rol == 2) { // Veterinario ?>
                <a href="Citas/listar.php">Ver Citas</a>
                <a href="Historiales_Medicos/crear.php">Gestionar Historial</a>
            <?php } elseif ($rol == 3) { // Recepcionista ?>
                <a href="Citas/listar.php">Gestionar Citas</a>
                <a href="Ventas/crear.php">Gestionar Ventas</a>
                <a href="Productos/listar.php">Gestionar Productos</a>
                <a href="Servicios/listar.php">Gestionar Servicios</a>
            <?php } elseif ($rol == 1) { // Administrador ?>
                <a href="Usuarios/listar.php">Gestionar Usuarios</a>
                <a href="reportes.php">Reportes</a>
                <a href="Mascotas/listar.php">Mascotas</a>
                <a href="Citas/listar.php">Citas</a>
                <a href="Ventas/listar.php">Ventas</a>
                <a href="Historiales_Medicos/listar.php">Historial</a>
            <?php } ?>
            <a href="cerrar_sesion.php">Cerrar Sesión</a>
        </nav>
    </div>
</body>
</html>