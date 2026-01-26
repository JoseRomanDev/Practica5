<?php

require_once __DIR__ . '/Database.php';

class Action {
    
    public static function createAction($userId, $type, $newEmail = null) {
        $conn = Database::connect();
        
        $token = bin2hex(random_bytes(125));
        
        $stmt = $conn->prepare("INSERT INTO actions (user_id, token, type, new_email) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $userId, $token, $type, $newEmail);
        
        if ($stmt->execute()) {
            return ['success' => true, 'token' => $token];
        }
        return ['success' => false];
    }
    
    public static function getActionByToken($token) {
        $conn = Database::connect();
        
        $stmt = $conn->prepare("SELECT id, user_id, type, new_email, tiempo_ejecucion FROM actions WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public static function executeAction($actionId) {
        $conn = Database::connect();
        
        $stmt = $conn->prepare("UPDATE actions SET tiempo_ejecucion = NOW() WHERE id = ?");
        $stmt->bind_param("i", $actionId);
        return $stmt->execute();
    }
    
    public static function cleanExpiredActions() {
        $conn = Database::connect();
        
        $stmt = $conn->prepare("DELETE FROM actions WHERE tiempo_ejecucion IS NULL AND tiempo_peticion < DATE_SUB(NOW(), INTERVAL 24 HOUR)");
        return $stmt->execute();
    }
}
?>
