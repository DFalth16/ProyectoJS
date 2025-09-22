<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Bienvenido</h1>
        <nav>
            <?php if ($role == 4) { // Cliente ?>
                <a href="pets.php">Gestionar Mascotas</a>
                <a href="appointments_client.php">Agendar Citas</a>
                <a href="history_client.php">Ver Historial</a>
                <a href="sales_client.php">Ver Notas de Venta</a>
            <?php } elseif ($role == 2) { // Vet ?>
                <a href="appointments_vet.php">Ver Citas</a>
                <a href="history_vet.php">Gestionar Historial</a>
                <a href="schedule_vet.php">Gestionar Horarios</a>
            <?php } elseif ($role == 3) { // Receptionist ?>
                <a href="appointments_rec.php">Gestionar Citas</a>
                <a href="sales_rec.php">Gestionar Ventas</a>
                <a href="products_rec.php">Gestionar Inventario</a>
            <?php } elseif ($role == 1) { // Admin ?>
                <a href="users_admin.php">Gestionar Usuarios</a>
                <a href="reports.php">Reportes</a>
                <!-- Acceso a todo -->
                <a href="pets.php">Mascotas</a>
                <a href="appointments_rec.php">Citas</a>
                <a href="sales_rec.php">Ventas</a>
                <a href="history_vet.php">Historial</a>
            <?php } ?>
            <a href="logout.php">Cerrar Sesión</a>
        </nav>
    </div>
</body>
</html>