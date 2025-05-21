<?php
$servername = "db";  // Nombre del servicio en docker-compose
$username = "root";
$password = "root";  // Debe coincidir con MYSQL_ROOT_PASSWORD
$database = "greenpath";

$conexion = new mysqli($servername, $username, $password, $database);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>