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
    } elseif ($actionData['type'] !== 'CHANGE_EMAIL') {
        $error = 'Tipo de acción inválido';
    } else {
        if (User::updateEmail($actionData['user_id'], $actionData['new_email']) && Action::executeAction($actionData['id'])) {
            header("Location: /EmailPhp/Practica5/views/Dashboard/dashboard.html?success=" . urlencode('Email actualizado correctamente'));
            exit;
        } else {
            $error = 'Error al actualizar el email';
        }
    }
}

if ($error) {
    header("Location: /EmailPhp/Practica5/views/Auth/confirm_email.html?token=" . urlencode($token) . "&error=" . urlencode($error));
    exit;
}
?>
