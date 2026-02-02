<?php

require_once __DIR__ . '/../../Connections/Database.php';
require_once __DIR__ . '/../../Connections/Action.php';
require_once __DIR__ . '/../../Connections/User.php';

$error = '';
$success = '';
$token = $_GET['token'] ?? '';

if (empty($token)) {
    $error = 'Token inválido';
} else {
    $actionData = Action::getActionByToken($token);
    
    if (!$actionData) {
        $error = 'Token no encontrado o expirado';
    } elseif ($actionData['tiempo_ejecucion'] !== null) {
        $error = 'Este enlace ya ha sido utilizado';
    } elseif ($actionData['type'] !== 'RESET_PASSWORD') {
        $error = 'Tipo de acción inválido';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)) {
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    
    if (empty($password) || empty($password_confirm)) {
        $error = 'Completa todos los campos';
    } elseif ($password !== $password_confirm) {
        $error = 'Las contraseñas no coinciden';
    } elseif (strlen($password) < 8) {
        $error = 'La contraseña debe tener al menos 8 caracteres';
    } else {
        if (User::updatePassword($actionData['user_id'], $password) && Action::executeAction($actionData['id'])) {
            header("Location: ../../views/Auth/login.html?success=" . urlencode('Contraseña actualizada correctamente. Ya puedes iniciar sesión.'));
            exit;
        } else {
            $error = 'Error al actualizar la contraseña';
        }
    }
}

if ($error) {
    header("Location: ../../views/Auth/reset_password.html?token=" . urlencode($token) . "&error=" . urlencode($error));
    exit;
}
?>
