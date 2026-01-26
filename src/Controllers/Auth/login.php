<?php

require_once __DIR__ . '/../../Connections/Database.php';
require_once __DIR__ . '/../../Connections/User.php';
require_once __DIR__ . '/../../Connections/Session.php';

Session::init();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Completa todos los campos';
    } else {
        $userData = User::getUserByEmail($email);
        
        if (!$userData) {
            $error = 'Email o contraseña incorrectos';
        } elseif (!$userData['active']) {
            $error = 'Tu cuenta no está activa. Revisa tu email de confirmación';
        } elseif (!password_verify($password, $userData['password'])) {
            $error = 'Email o contraseña incorrectos';
        } else {
            Session::setUser($userData['id'], $userData['username'], $userData['email']);
            header('Location: /EmailPhp/Practica5/views/Dashboard/dashboard.html');
            exit;
        }
    }
}

// Si hay error, redirigir con mensaje
if ($error) {
    header("Location: /EmailPhp/Practica5/views/Auth/login.html?error=" . urlencode($error));
    exit;
}
?>
