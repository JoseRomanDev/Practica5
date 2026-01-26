<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba de Envío de Email</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .test-container {
            max-width: 600px;
        }
        .log-box {
            background: #f5f5f5;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
            max-height: 400px;
            overflow-y: auto;
            font-family: monospace;
            font-size: 12px;
            white-space: pre-wrap;
            word-break: break-all;
        }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
    </style>
</head>
<body>
    <div class="container test-container">
        <h1>Prueba de Envío de Email</h1>
        
        <form method="POST">
            <div class="form-group">
                <label for="email">Email de prueba</label>
                <input type="email" id="email" name="email" placeholder="tu@email.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="name">Nombre</label>
                <input type="text" id="name" name="name" placeholder="Tu nombre" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="type">Tipo de email</label>
                <select id="type" name="type" required>
                    <option value="activation">Email de activación</option>
                    <option value="reset">Email de reset de contraseña</option>
                    <option value="change">Email de cambio de email</option>
                </select>
            </div>
            
            <button type="submit">Enviar Email de Prueba</button>
        </form>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'src/Connections/Email.php';
            
            $email = $_POST['email'] ?? '';
            $name = $_POST['name'] ?? '';
            $type = $_POST['type'] ?? '';
            $token = bin2hex(random_bytes(32));
            
            echo '<div class="log-box">';
            echo '<span class="info">🔄 Enviando email...</span>' . "\n\n";
            
            $result = false;
            switch ($type) {
                case 'activation':
                    $result = Email::sendActivationEmail($email, $name, $token);
                    echo "Tipo: Email de activación\n";
                    break;
                case 'reset':
                    $result = Email::sendResetPasswordEmail($email, $name, $token);
                    echo "Tipo: Email de reset\n";
                    break;
                case 'change':
                    $result = Email::sendEmailChangeConfirmation($email, $name, $token);
                    echo "Tipo: Email de cambio\n";
                    break;
            }
            
            echo "Email: $email\n";
            echo "Nombre: $name\n";
            echo "Token: $token\n\n";
            
            if ($result) {
                echo '<span class="success">✓ Email enviado correctamente</span>';
            } else {
                echo '<span class="error">✗ Error al enviar el email</span>';
            }
            
            echo "\n\n<strong>Revisa el archivo emails.log para más detalles</strong>";
            echo '</div>';
        }
        ?>
        
        <div style="margin-top: 30px; padding: 15px; background: #e8f5e9; border-radius: 4px;">
            <h3>Información:</h3>
            <ul>
                <li>Este formulario es para <strong>testing</strong> únicamente</li>
                <li>Los emails se registran en <code>emails.log</code></li>
                <li>Configura tus credenciales en <code>src/Connections/Email.php</code></li>
                <li>Lee <code>CONFIGURAR_EMAIL.md</code> para más instrucciones</li>
            </ul>
        </div>
    </div>
</body>
</html>
