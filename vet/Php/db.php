<?php
$host = 'localhost';
$bd = 'vet_db';
$usuario = 'root';
$contrasena = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$bd", $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>