<?php

require_once __DIR__ . '/../../Connections/Session.php';
require_once __DIR__ . '/../../Connections/User.php';

Session::init();

if (!Session::isActive()) {
    header('Location: ../../views/Auth/login.html');
    exit;
}

$error = '';
$uploadDir = __DIR__ . '/../../pics/profile/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $file = $_FILES['photo'];
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
        
        if (!in_array($mimeType, $allowedMimes)) {
            $error = 'Formato de imagen no permitido';
        } elseif ($file['size'] > 5 * 1024 * 1024) {
            $error = 'Archivo muy grande (máximo 5MB)';
        } else {
            $extension = match($mimeType) {
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
                'image/webp' => 'webp',
                'image/svg+xml' => 'svg',
                default => 'jpg'
            };
            
            $filename = bin2hex(random_bytes(32)) . '.' . $extension;
            $filepath = $uploadDir . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                // Guardar solo el nombre del archivo, no la ruta completa
                if (User::updatePhoto($_SESSION['user_id'], $filename)) {
                    header("Location: ../../views/Dashboard/dashboard.html?success=" . urlencode('Foto actualizada correctamente'));
                    exit;
                } else {
                    unlink($filepath);
                    $error = 'Error al guardar la foto';
                }
            } else {
                $error = 'Error al subir la foto';
            }
        }
    } else {
        $error = 'Error en la carga del archivo';
    }
}

if ($error) {
    header("Location: ../../views/Dashboard/upload_photo.html?error=" . urlencode($error));
    exit;
}
?>
