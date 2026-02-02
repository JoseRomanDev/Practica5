<?php

require_once __DIR__ . '/../../Connections/Database.php';
require_once __DIR__ . '/../../Connections/User.php';
require_once __DIR__ . '/../../Connections/Action.php';
require_once __DIR__ . '/../../Connections/Email.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Email inválido';
    } else {
        $userData = User::getUserByEmail($email);
        
        if ($userData) {
            $actionResult = Action::createAction($userData['id'], 'RESET_PASSWORD');
            
            if ($actionResult['success']) {
                Email::sendResetPasswordEmail($email, $userData['username'], $actionResult['token']);
                header("Location: ../../views/Auth/login.html?success=" . urlencode('Si la cuenta existe, recibirás un email con instrucciones para recuperar tu contraseña.'));
                exit;
            }
        } else {
            header("Location: ../../views/Auth/login.html?success=" . urlencode('Si la cuenta existe, recibirás un email con instrucciones para recuperar tu contraseña.'));
            exit;
        }
    }
}

if ($message) {
    header("Location: ../../views/Auth/forgot_password.html?error=" . urlencode($message));
    exit;
}
?>
