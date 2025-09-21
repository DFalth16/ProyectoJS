<?php
session_start();
include '../db.php';
if (!isset($_SESSION['id_usuario']) || ($_SESSION['rol'] != 3 && $_SESSION['rol'] != 1)) {
    header('Location: ../inicio_sesion.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: listar.php');
    exit;
}
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT id_venta FROM detalles_ventas WHERE id = ?");
$stmt->execute([$id]);
$det = $stmt->fetch();

$stmt = $pdo->prepare("DELETE FROM detalles_ventas WHERE id = ?");
$stmt->execute([$id]);
header('Location: listar.php?id_venta=' . $det['id_venta']);
exit;
?>