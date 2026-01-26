<?php
// Script para ejecutar data.sql

$HOST = "localhost";
$USER = "root"; // Usuario por defecto de XAMPP
$PASS = ""; // Contraseña vacía por defecto

// Conectar sin especificar base de datos
$conn = new mysqli($HOST, $USER, $PASS);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

echo "✓ Conectado a MySQL\n";

// Leer el archivo SQL
$sql = file_get_contents(__DIR__ . '/data.sql');

// Ejecutar el SQL
if ($conn->multi_query($sql)) {
    echo "✓ Script SQL ejecutado correctamente\n";
    
    // Consumir todos los resultados
    do {
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->next_result());
} else {
    echo "✗ Error: " . $conn->error . "\n";
}

$conn->close();
echo "\nBase de datos lista para usar.\n";
?>
