<?php

require_once __DIR__ . '/../../Connections/Session.php';
require_once __DIR__ . '/../../Connections/User.php';

Session::init();

if (!Session::isActive()) {
    header('Location: ../../views/Auth/login.html');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $error = 'Completa todos los campos';
    } elseif ($newPassword !== $confirmPassword) {
        $error = 'Las nuevas contraseñas no coinciden';
    } elseif (strlen($newPassword) < 8) {
        $error = 'La contraseña debe tener al menos 8 caracteres';
    } else {
        $userData = User::getUserById($_SESSION['user_id']);
        
        if (!password_verify($currentPassword, $userData['password'])) {
            $error = 'Contraseña actual incorrecta';
        } else {
            if (User::updatePassword($_SESSION['user_id'], $newPassword)) {
                header("Location: ../../views/Dashboard/dashboard.html?success=" . urlencode('Contraseña actualizada correctamente'));
                exit;
            } else {
                $error = 'Error al actualizar la contraseña';
            }
        }
    }
}

if ($error) {
    header("Location: ../../views/Dashboard/change_password.html?error=" . urlencode($error));
    exit;
}
?>
