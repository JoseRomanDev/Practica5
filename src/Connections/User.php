<?php

require_once __DIR__ . '/Database.php';

class User {
    
    public static function register($username, $name, $surname, $genre, $email, $password) {
        $conn = Database::connect();
        
        if (self::userExists($email, $username)) {
            return ['success' => false, 'message' => 'El usuario o email ya existe'];
        }
        
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        $stmt = $conn->prepare("INSERT INTO users (username, name, surname, genre, email, password, active) VALUES (?, ?, ?, ?, ?, ?, 0)");
        $stmt->bind_param("ssssss", $username, $name, $surname, $genre, $email, $hashedPassword);
        
        if ($stmt->execute()) {
            return ['success' => true, 'user_id' => $conn->insert_id];
        }
        return ['success' => false, 'message' => 'Error al registrar'];
    }
    
    public static function userExists($email, $username = null) {
        $conn = Database::connect();
        
        if ($username) {
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
            $stmt->bind_param("ss", $email, $username);
        } else {
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
        }
        
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }
    
    public static function getUserByEmail($email) {
        $conn = Database::connect();
        
        $stmt = $conn->prepare("SELECT id, username, name, surname, email, password, active, photo FROM users WHERE email = ?");
        
        if (!$stmt) {
            die("Error en consulta: " . $conn->error);
        }
        
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public static function getUserById($id) {
        $conn = Database::connect();
        
        $stmt = $conn->prepare("SELECT id, username, name, surname, genre, email, password, active, photo FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public static function updateProfile($userId, $name, $surname, $genre) {
        $conn = Database::connect();
        
        $stmt = $conn->prepare("UPDATE users SET name = ?, surname = ?, genre = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $surname, $genre, $userId);
        return $stmt->execute();
    }
    
    public static function updatePassword($userId, $newPassword) {
        $conn = Database::connect();
        
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashedPassword, $userId);
        return $stmt->execute();
    }
    
    public static function updatePhoto($userId, $photoPath) {
        $conn = Database::connect();
        
        $stmt = $conn->prepare("UPDATE users SET photo = ? WHERE id = ?");
        $stmt->bind_param("si", $photoPath, $userId);
        return $stmt->execute();
    }
    
    public static function updateEmail($userId, $newEmail) {
        $conn = Database::connect();
        
        if (self::userExists($newEmail)) {
            return false;
        }
        
        $stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
        $stmt->bind_param("si", $newEmail, $userId);
        return $stmt->execute();
    }
    
    public static function activateAccount($userId) {
        $conn = Database::connect();
        
        $stmt = $conn->prepare("UPDATE users SET active = 1 WHERE id = ?");
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }
}
?>
