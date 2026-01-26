<?php
require_once __DIR__ . '/../../Connections/Session.php';
require_once __DIR__ . '/../../Connections/User.php';

// Validar sesión
Session::init();
if (!Session::isActive()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Sesión no válida']);
    exit;
}

// Obtener ID del usuario de la sesión
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Usuario no identificado']);
    exit;
}

// Obtener datos del usuario
$user = User::getUserById($userId);

header('Content-Type: application/json');

if ($user) {
    $photoPath = '';
    if ($user['photo']) {
        // Servir foto a través del controlador serve_photo.php
        $photoPath = '../../src/Controllers/Dashboard/serve_photo.php?file=' . urlencode($user['photo']);
    }
    
    echo json_encode([
        'success' => true,
        'username' => $user['username'],
        'name' => $user['name'],
        'surname' => $user['surname'],
        'email' => $user['email'],
        'genre' => $user['genre'],
        'photo' => $photoPath
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
}
?>
