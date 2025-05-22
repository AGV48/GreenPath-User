<?php
include "../config/conexion.php";

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];

// ... (validaciones previas permanecen iguales) ...

try {
    // Verificar si el email existe
    $stmt = $conexion->prepare("SELECT correo FROM usuarios WHERE correo = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $message = urlencode("Ya existe un usuario con ese email");
        header("Location: ../front/register.php?status=error&message=$message");
        exit();
    }

    // Hash de la contraseña usando password_hash()
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insertar nuevo usuario con la contraseña hasheada
    $insert = $conexion->prepare("INSERT INTO usuarios (nombre, correo, contrasena, puntos) VALUES (:name, :email, :password, 0)");
    $insert->bindParam(':name', $name);
    $insert->bindParam(':email', $email);
    $insert->bindParam(':password', $hashedPassword);
    
    if ($insert->execute()) {
        $message = urlencode("Usuario registrado exitosamente");
        header("Location: ../front/register.php?status=success&message=$message");
    } else {
        $message = urlencode("Error al registrarse. Por favor, inténtalo de nuevo");
        header("Location: ../front/register.php?status=error&message=$message");
    }
} catch (PDOException $e) {
    $message = urlencode("Error en el sistema: " . $e->getMessage());
    header("Location: ../front/register.php?status=error&message=$message");
}
?>