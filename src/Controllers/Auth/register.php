<?php

require_once __DIR__ . '/../../Connections/Database.php';
require_once __DIR__ . '/../../Connections/User.php';
require_once __DIR__ . '/../../Connections/Action.php';
require_once __DIR__ . '/../../Connections/Email.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $surname = trim($_POST['surname'] ?? '');
    $genre = $_POST['genre'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    
    if (empty($username) || empty($name) || empty($surname) || empty($genre) || empty($email) || empty($password)) {
        $error = 'Completa todos los campos';
    } elseif ($password !== $password_confirm) {
        $error = 'Las contraseñas no coinciden';
    } elseif (strlen($password) < 8) {
        $error = 'La contraseña debe tener al menos 8 caracteres';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email inválido';
    } else {
        $result = User::register($username, $name, $surname, $genre, $email, $password);
        
        if ($result['success']) {
            $actionResult = Action::createAction($result['user_id'], 'ACTIVE_ACCOUNT');
            
            if ($actionResult['success']) {
                Email::sendActivationEmail($email, $username, $actionResult['token']);
                header("Location: ../../views/Auth/login.html?success=" . urlencode('Registro exitoso. Revisa tu email para activar tu cuenta.'));
                exit;
            } else {
                $error = 'Error al generar token de activación';
            }
        } else {
            $error = $result['message'] ?? 'Error en el registro';
        }
    }
}

if ($error) {
    header("Location: ../../views/Auth/register.html?error=" . urlencode($error));
    exit;
}
?>
