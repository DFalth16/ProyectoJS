<?php
session_start();
include 'db.php';
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 1) {
    header('Location: inicio_sesion.php');
    exit;
}

// Lógica para reportes (consultas con filtros)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ejemplo: filtro por fecha, cliente, etc. para ventas, historial, citas
    // Usa queries como SELECT ... WHERE fecha BETWEEN ? AND ? AND id_cliente = ?
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reportes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="contenedor">
        <h1>Reportes y Estadísticas</h1>
        <h2>Reporte de Notas de Venta</h2>
        <form method="POST">
            <input type="text" name="id_cliente" placeholder="ID Cliente">
            <input type="date" name="fecha_inicio" placeholder="Fecha Inicio">
            <input type="date" name="fecha_fin" placeholder="Fecha Fin">
            <input type="text" name="item" placeholder="Item">
            <button type="submit">Generar</button>
        </form>
        <!-- Tabla con resultados -->
        <table>
            <tr><th>ID Venta</th><th>Fecha</th><th>Cliente</th><th>Total</th></tr>
            <!-- Llenar con datos -->
        </table>

        <h2>Reporte de Historial Veterinario</h2>
        <!-- Form y tabla similar -->

        <h2>Reporte de Citas</h2>
        <!-- Form y tabla similar -->
    </div>
</body>
</html>