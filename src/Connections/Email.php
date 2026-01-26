<?php

class Email {
    private const FROM_EMAIL = 'noreply@practica5.local';
    private const FROM_NAME = 'Sistema de Autenticación';
    private const LOG_FILE = __DIR__ . '/../../emails.log';
    
    public static function sendActivationEmail($userEmail, $username, $token) {
        $activationLink = "http://localhost/EmailPhp/Practica5/src/Controllers/Auth/activate.php?token=" . $token;
        
        $subject = "Activa tu cuenta";
        $message = "Hola $username,\n\n";
        $message .= "Gracias por registrarte. Haz clic en el siguiente enlace para activar tu cuenta:\n\n";
        $message .= $activationLink . "\n\n";
        $message .= "Este enlace expira en 24 horas.\n\n";
        $message .= "Saludos,\nEl equipo";
        
        return self::send($userEmail, $subject, $message, "activation");
    }
    
    public static function sendResetPasswordEmail($userEmail, $username, $token) {
        $resetLink = "http://localhost/EmailPhp/Practica5/src/Controllers/Auth/reset_password.php?token=" . $token;
        
        $subject = "Recupera tu contraseña";
        $message = "Hola $username,\n\n";
        $message .= "Hemos recibido una solicitud para resetear tu contraseña. Haz clic en el siguiente enlace:\n\n";
        $message .= $resetLink . "\n\n";
        $message .= "Este enlace expira en 24 horas.\n\n";
        $message .= "Si no solicitaste esto, ignora este email.\n\n";
        $message .= "Saludos,\nEl equipo";
        
        return self::send($userEmail, $subject, $message, "reset_password");
    }
    
    public static function sendEmailChangeConfirmation($newEmail, $username, $token) {
        $confirmLink = "http://localhost/EmailPhp/Practica5/src/Controllers/Auth/confirm_email.php?token=" . $token;
        
        $subject = "Confirma tu nuevo email";
        $message = "Hola $username,\n\n";
        $message .= "Haz clic en el siguiente enlace para confirmar tu nuevo email:\n\n";
        $message .= $confirmLink . "\n\n";
        $message .= "Este enlace expira en 24 horas.\n\n";
        $message .= "Saludos,\nEl equipo";
        
        return self::send($newEmail, $subject, $message, "change_email");
    }
    
    private static function send($to, $subject, $message, $type = "general") {
        // Validar que el email sea válido
        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            self::logEmail($to, $subject, "FAILED_INVALID_EMAIL", $type);
            return false;
        }
        
        $headers = 'From: ' . self::FROM_NAME . ' <' . self::FROM_EMAIL . '>' . "\r\n" .
                   'Reply-To: ' . self::FROM_EMAIL . "\r\n" .
                   'X-Mailer: PHP/' . phpversion() . "\r\n" .
                   "MIME-Version: 1.0\r\n" .
                   "Content-Type: text/plain; charset=UTF-8\r\n";
        
        // Usar mail() con error checking mejorado
        $result = @mail($to, $subject, $message, $headers);
        
        // Logging detallado
        if ($result) {
            $status = "SENT_OK";
        } else {
            $status = "FAILED_MAIL_FUNCTION";
            // Intentar capturar error
            if (!ini_get('sendmail_from')) {
                $status .= " (NO_SENDMAIL_FROM)";
            }
        }
        
        $logMessage = date('Y-m-d H:i:s') . " | TYPE: $type | TO: $to | SUBJECT: $subject | STATUS: $status\n";
        file_put_contents(self::LOG_FILE, $logMessage, FILE_APPEND);
        
        return $result;
    }
    
    private static function logEmail($to, $subject, $status, $type) {
        $logMessage = date('Y-m-d H:i:s') . " | TYPE: $type | TO: $to | SUBJECT: $subject | STATUS: $status\n";
        file_put_contents(self::LOG_FILE, $logMessage, FILE_APPEND);
    }
}
?>


