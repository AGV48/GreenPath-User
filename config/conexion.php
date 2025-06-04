<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

if (!in_array('pgsql', PDO::getAvailableDrivers())) {
    die('Error: El driver PDO para PostgreSQL no está instalado. Contacta al administrador.');
}

try {
    $host = $_ENV['DB_HOST'];
    $port = $_ENV['DB_PORT'] ?? '5432';
    $dbname = $_ENV['DB_NAME'];
    $user = $_ENV['DB_USER'];
    $password = $_ENV['DB_PASSWORD'];
    
    // Configuración DSN con SSL
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    
    // Opciones de PDO
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5,
        PDO::ATTR_PERSISTENT => false
    ];
    
    // Para versiones recientes de PHP que soportan SSL verify
    if (defined('PDO::PGSQL_ATTR_SSL_MODE') && defined('PDO::PGSQL_ATTR_SSL_VERIFY')) {
        $options[PDO::PGSQL_ATTR_SSL_MODE] = PDO::PGSQL_SSL_VERIFY_CA;
        $options[PDO::PGSQL_ATTR_SSL_VERIFY] = false;
    } else {
        // Alternativa para versiones más antiguas
        $dsn .= ";sslmode=require";
    }
    
    $conexion = new PDO($dsn, $user, $password, $options);
    $conexion->exec("SET NAMES 'UTF8'");
    
} catch (PDOException $e) {
    error_log("Error de conexión: " . $e->getMessage());
    echo ($e);
    die("Lo sentimos, estamos experimentando problemas técnicos. Por favor intente más tarde.");
}
?>