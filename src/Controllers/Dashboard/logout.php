<?php

require_once __DIR__ . '/../../Connections/Session.php';

Session::init();
Session::destroy();

header('Location: ../../views/Auth/login.html');
exit;
?>
