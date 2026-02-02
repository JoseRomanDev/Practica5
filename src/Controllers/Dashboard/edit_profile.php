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
    $name = trim($_POST['name'] ?? '');
    $surname = trim($_POST['surname'] ?? '');
    $genre = $_POST['genre'] ?? '';
    
    if (empty($name) || empty($surname) || empty($genre)) {
        $error = 'Completa todos los campos';
    } else {
        if (User::updateProfile($_SESSION['user_id'], $name, $surname, $genre)) {
            header("Location: ../../views/Dashboard/dashboard.html?success=" . urlencode('Perfil actualizado correctamente'));
            exit;
        } else {
            $error = 'Error al actualizar el perfil';
        }
    }
}

if ($error) {
    header("Location: ../../views/Dashboard/edit_profile.html?error=" . urlencode($error));
    exit;
}
?>
