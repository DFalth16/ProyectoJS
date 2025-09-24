<?php
// inicio_sesion_api.php
session_start();
header('Content-Type: application/json; charset=utf-8');

require 'db.php'; // debe definir $pdo (PDO)

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// leer JSON o form-data
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) $input = $_POST;

$nombre_usuario = trim($input['nombre_usuario'] ?? '');
$contrasena = $input['contrasena'] ?? '';

if ($nombre_usuario === '' || $contrasena === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Usuario y contraseña requeridos.']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT u.id, u.nombre_usuario, u.contrasena, u.rol, u.nombre, r.nombre AS rol_nombre
                           FROM usuarios u
                           LEFT JOIN roles r ON u.rol = r.id
                           WHERE u.nombre_usuario = ? LIMIT 1");
    $stmt->execute([$nombre_usuario]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Usuario o contraseña incorrectos.']);
        exit;
    }

  if ($contrasena !== $usuario['contrasena']) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuario o contraseña incorrectos.']);
    exit;
}


    // Login OK: guardar sesión mínima
    $_SESSION['id_usuario'] = (int)$usuario['id'];
    $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
    $_SESSION['nombre'] = $usuario['nombre'];
    $_SESSION['rol'] = (int)$usuario['rol'];
    $_SESSION['rol_nombre'] = $usuario['rol_nombre'] ?? null;
    $_SESSION['logged_at'] = time();

    echo json_encode([
        'success' => true,
        'message' => 'Autenticación correcta.',
        'redirect' => 'panel.php',
        'rol' => $_SESSION['rol'],
        'rol_nombre' => $_SESSION['rol_nombre']
    ]);
    exit;

} catch (Exception $e) {
    http_response_code(500);
    error_log('Login error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error interno.']);
    exit;
}
