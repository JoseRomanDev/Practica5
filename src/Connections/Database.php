<?php

class Database {
    private static $conn = null;
    
    public static function connect() {
        if (self::$conn === null) {
            $HOST = "localhost";
            $DB = "practica5";
            $PORT = 3306;
            $USER = "root";
            $PASS = "";
            
            mysqli_report(MYSQLI_REPORT_STRICT);
            
            try {
                self::$conn = new mysqli($HOST, $USER, $PASS, $DB, $PORT);
                if (self::$conn->connect_error) {
                    throw new Exception("Error de conexión: " . self::$conn->connect_error);
                }
                self::$conn->set_charset("utf8mb4");
            } catch (Exception $e) {
                die("Error de BD: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
}
?>
