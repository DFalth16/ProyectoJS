<?php
session_start();
include '../db.php';
if (!isset($_SESSION['id_usuario']) || ($_SESSION['rol'] != 3 && $_SESSION['rol'] != 1)) {
    header('Location: ../inicio_sesion.php');
    exit;
}

if (!isset($_GET['id_venta'])) {
    echo "ID de venta requerido.";
    exit;
}
$id_venta = $_GET['id_venta'];

$stmt = $pdo->query("SELECT * FROM productos");
$productos = $stmt->fetchAll();
$stmt = $pdo->query("SELECT * FROM servicios");
$servicios = $stmt->fetchAll();

// Create
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo_item = $_POST['tipo_item'];
    $id_item = $_POST['id_item'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio']; // Obtener de BD

    $stmt = $pdo->prepare("INSERT INTO detalles_ventas (id_venta, tipo_item, id_item, cantidad, precio) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$id_venta, $tipo_item, $id_item, $cantidad, $precio]);

    // Actualizar total de venta
    // Calcular y UPDATE ventas.total

    header('Location: listar.php?id_venta=' . $id_venta);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Crear Detalle de Venta</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="contenedor">
        <h1>Agregar Detalle a Venta ID: <?php echo $id_venta; ?></h1>
        <form method="POST">
            <select name="tipo_item" required>
                <option value="producto">Producto</option>
                <option value="servicio">Servicio</option>
            </select>
            <select name="id_item" required>
                <!-- Llenar dinámicamente con JS o separate forms -->
                <?php // Para simplicidad, lista todos; usa JS para filter por tipo ?>
                <?php foreach ($productos as $prod): ?>
                    <option value="<?php echo $prod['id']; ?>">(Producto) <?php echo $prod['nombre']; ?></option>
                <?php endforeach; ?>
                <?php foreach ($servicios as $serv): ?>
                    <option value="<?php echo $serv['id']; ?>">(Servicio) <?php echo $serv['nombre']; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="cantidad" placeholder="Cantidad" required>
            <input type="number" step="0.01" name="precio" placeholder="Precio" required>
            <button type="submit">Agregar</button>
        </form>
    </div>
</body>
</html>