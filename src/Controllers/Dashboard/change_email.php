<?php

require_once __DIR__ . '/../../Connections/Session.php';
require_once __DIR__ . '/../../Connections/User.php';
require_once __DIR__ . '/../../Connections/Action.php';
require_once __DIR__ . '/../../Connections/Email.php';

Session::init();

if (!Session::isActive()) {
    header('Location: ../../views/Auth/login.html');
    exit;
}

$error = '';
$userData = User::getUserById($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newEmail = trim($_POST['new_email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($newEmail) || empty($password)) {
        $error = 'Completa todos los campos';
    } elseif (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email inválido';
    } elseif ($newEmail === $userData['email']) {
        $error = 'Debes ingresar un email diferente';
    } elseif (!password_verify($password, $userData['password'])) {
        $error = 'Contraseña incorrecta';
    } else {
        $actionResult = Action::createAction($_SESSION['user_id'], 'CHANGE_EMAIL', $newEmail);
        
        if ($actionResult['success']) {
            Email::sendEmailChangeConfirmation($newEmail, $userData['username'], $actionResult['token']);
            header("Location: ../../views/Dashboard/dashboard.html?success=" . urlencode('Revisa tu nuevo email para confirmar el cambio'));
            exit;
        } else {
            $error = 'Error al generar token de confirmación';
        }
    }
}

if ($error) {
    header("Location: ../../views/Dashboard/change_email.html?error=" . urlencode($error));
    exit;
}
?>
