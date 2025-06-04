<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Verificar si el driver está disponible
if (!in_array('pgsql', PDO::getAvailableDrivers())) {
    die('Error: El driver PDO para PostgreSQL no está instalado. Contacta al administrador.');
}

// Configuración de la conexión
$host = $_ENV['DB_HOST'];
$port = $_ENV['DB_PORT'];
$dbname = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];

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
    echo ($e);
    die("Lo sentimos, estamos experimentando problemas técnicos. Por favor intente más tarde.");
}
?>