<?php
session_start();
include "../config/conexion.php";

$email = $_POST['email'];
$password = $_POST['password'];

if (empty($email) || empty($password)) {
    echo '<script>
            alert("Por favor, completa todos los campos");
            window.location = "../index.php";
          </script>';
    exit();
}

try {
    // Obtener el hash almacenado para el usuario
    $query = "SELECT nombre, correo, puntos, contrasena FROM usuarios WHERE correo = :email";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verificar la contraseña usando password_verify()
        if (password_verify($password, $user_data['contrasena'])) {
            $_SESSION['user_email'] = $user_data['correo'];
            $_SESSION['user_name'] = $user_data['nombre'];
            
            echo '<script>window.location = "../front/dashboard.php";</script>';
        } else {
            $message = urlencode("Usuario o contraseña incorrectos");
            header("Location: ../index.php?status=error&message=$message");
        }
    } else {
        $message = urlencode("Usuario o contraseña incorrectos");
        header("Location: ../index.php?status=error&message=$message");
    }
} catch (PDOException $e) {
    $message = urlencode("Error en el sistema: " . $e->getMessage());
    header("Location: ../index.php?status=error&message=$message");
}
?>