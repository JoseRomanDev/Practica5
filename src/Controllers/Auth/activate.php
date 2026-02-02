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
    } elseif ($actionData['type'] !== 'ACTIVE_ACCOUNT') {
        $error = 'Tipo de acción inválido';
    } else {
        if (User::activateAccount($actionData['user_id']) && Action::executeAction($actionData['id'])) {
            header("Location: ../../views/Auth/login.html?success=" . urlencode('Cuenta activada correctamente. Ahora puedes iniciar sesión.'));
            exit;
        } else {
            $error = 'Error al activar la cuenta';
        }
    }
}

if ($error) {
    header("Location: ../../views/Auth/activate.html?token=" . urlencode($token) . "&error=" . urlencode($error));
    exit;
}
?>
