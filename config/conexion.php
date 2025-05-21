<?php
// Verificar si el driver está disponible
if (!in_array('pgsql', PDO::getAvailableDrivers())) {
    die('Error: El driver PDO para PostgreSQL no está instalado. Contacta al administrador.');
}

// Configuración (usa variables de entorno en producción)
$host = 'ep-round-tree-a551uewg-pooler.us-east-2.aws.neon.tech';
$port = '5432';
$dbname = 'Greenpath';
$user = 'AGV';
$password = 'TtfvlRibHh93';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
    $conexion = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5
    ]);
    
    // Configuración adicional
    $conexion->exec("SET NAMES 'UTF8'");
    
} catch (PDOException $e) {
    // Mensaje más informativo
    $errorMsg = "Error de conexión a la base de datos: \n";
    $errorMsg .= "Mensaje: " . $e->getMessage() . "\n";
    $errorMsg .= "Código: " . $e->getCode() . "\n";
    $errorMsg .= "Driver disponible: " . (in_array('pgsql', PDO::getAvailableDrivers()) ? 'Sí' : 'No');
    
    error_log($errorMsg);
    die("Lo sentimos, estamos experimentando problemas técnicos. Por favor intente más tarde.");
}
?>