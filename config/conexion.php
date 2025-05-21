<?php
// Habilita toda la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuración para Neon.tech (usa variables de entorno en producción)
$host = 'ep-round-tree-a551uewg-pooler.us-east-2.aws.neon.tech';
$port = '5432';
$dbname = 'Greenpath';
$user = 'AGV';
$password = 'TtfvlRibHh93';

try {
    // Cadena de conexión con opciones SSL
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5, // Timeout de 5 segundos
    ];
    
    $conexion = new PDO($dsn, $user, $password, $options);
    
    // Si llegamos aquí, la conexión fue exitosa
    echo "¡Conexión exitosa a Neon.tech!";
    
    // Prueba una consulta simple
    $stmt = $conexion->query("SELECT 1 as test");
    $result = $stmt->fetch();
    print_r($result);
    
} catch (PDOException $e) {
    // Muestra el error completo
    die("Error de conexión: " . $e->getMessage() . 
        " (Código: " . $e->getCode() . ")");
}
?>