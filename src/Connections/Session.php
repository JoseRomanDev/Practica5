<?php

class Session {
    private const SESSION_TIMEOUT = 300; // 5 minutos de inactividad
    
    public static function init() {
        session_set_cookie_params([
            'lifetime' => self::SESSION_TIMEOUT,
            'path' => '/',
            'secure' => false,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        
        session_start();
        self::validateTimeout();
    }
    
    public static function validateTimeout() {
        if (isset($_SESSION['user_id'])) {
            $now = time();
            $lastActivity = $_SESSION['last_activity'] ?? $now;
            
            if ($now - $lastActivity > self::SESSION_TIMEOUT) {
                self::destroy();
                header("Location: ../views/Auth/login.html?timeout=1");
                exit;
            }
            
            $_SESSION['last_activity'] = $now;
        }
    }
    
    public static function setUser($userId, $username, $email) {
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['last_activity'] = time();
    }
    
    public static function getUser() {
        return $_SESSION['user_id'] ?? null;
    }
    
    public static function destroy() {
        $_SESSION = [];
        if (session_id() !== '') {
            session_destroy();
        }
    }
    
    public static function isActive() {
        return isset($_SESSION['user_id']);
    }
}
?>
