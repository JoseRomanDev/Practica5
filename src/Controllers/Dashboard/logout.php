<?php

require_once __DIR__ . '/../../Connections/Session.php';

Session::init();
Session::destroy();

header('Location: /EmailPhp/Practica5/views/Auth/login.html');
exit;
?>
