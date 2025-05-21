<?php
// Configuración para Render (usa variables de entorno)
$host = getenv('DB_HOST') ?: 'db'; // 'db' solo funciona en Docker
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: 'root';
$db   = getenv('DB_NAME') ?: 'greenpath';

// Intento de conexión con manejo de errores
try {
    $conexion = new mysqli($host, $user, $pass, $db);
    
    if ($conexion->connect_error) {
        throw new Exception("Conexión fallida: " . $conexion->connect_error);
    }
    
    // Configurar el charset si es necesario
    $conexion->set_charset("utf8mb4");
    
} catch (Exception $e) {
    // Mensaje de error más amigable para producción
    error_log("Error de base de datos: " . $e->getMessage());
    die("Lo sentimos, estamos experimentando problemas técnicos. Por favor intente más tarde.");
}
?>