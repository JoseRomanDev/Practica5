<?php
// Controlador para servir fotos de perfil
require_once __DIR__ . '/../../Connections/Session.php';

$filename = basename($_GET['file'] ?? '');

if (empty($filename)) {
    http_response_code(400);
    exit('No file specified');
}

// Validar que sea solo el nombre del archivo (sin paths)
if (strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
    http_response_code(400);
    exit('Invalid filename');
}

$filepath = __DIR__ . '/../../pics/profile/' . $filename;

// Verificar que el archivo existe
if (!file_exists($filepath)) {
    http_response_code(404);
    exit('File not found');
}

// Determinar mime type
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimetype = finfo_file($finfo, $filepath);
finfo_close($finfo);

// Servir el archivo
header('Content-Type: ' . $mimetype);
header('Content-Length: ' . filesize($filepath));
readfile($filepath);
exit;
?>
