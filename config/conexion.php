<?php
// Configuración para Neon.tech PostgreSQL
$host = getenv('DB_HOST') ?: 'ep-round-tree-a551uewg-pooler.us-east-2.aws.neon.tech';
$port = getenv('DB_PORT') ?: '5432';
$dbname = getenv('DB_NAME') ?: 'Greenpath';
$user = getenv('DB_USER') ?: 'AGV';
$password = getenv('DB_PASSWORD') ?: 'TtfvlRibHh93';

try {
    // Cadena de conexión para PostgreSQL con Neon
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
    $conexion = new PDO($dsn, $user, $password);
    
    // Configurar el manejo de errores
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
    // Configurar el encoding
    $conexion->exec("SET NAMES 'utf8mb4'");
    
} catch (PDOException $e) {
    error_log("Error de base de datos: " . $e->getMessage());
    die("Lo sentimos, estamos experimentando problemas técnicos. Por favor intente más tarde.");
}
?>