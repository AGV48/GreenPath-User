<?php
require_once 'config/conexion.php';

try {
    $stmt = $conexion->query("SELECT * FROM usuarios");
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Conexión exitosa a Neon.tech PostgreSQL</h2>";
    echo "<pre>";
    print_r($resultados);
    echo "</pre>";
} catch (PDOException $e) {
    echo "<h2>Error de conexión</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>