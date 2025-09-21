<?php
session_start();
include '../db.php';
if (!isset($_SESSION['id_usuario']) || ($_SESSION['rol'] != 4 && $_SESSION['rol'] != 1)) {
    header('Location: ../inicio_sesion.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: listar.php');
    exit;
}
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT id_dueno FROM mascotas WHERE id = ?");
$stmt->execute([$id]);
$masc = $stmt->fetch();

if (!$masc || ($masc['id_dueno'] != $_SESSION['id_usuario'] && $_SESSION['rol'] != 1)) {
    header('Location: listar.php');
    exit;
}

$stmt = $pdo->prepare("DELETE FROM mascotas WHERE id = ?");
$stmt->execute([$id]);
header('Location: listar.php');
exit;
?>  