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
    $query = "SELECT nombre, correo, puntos FROM usuarios WHERE correo = :email AND contrasena = :password";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['user_email'] = $user_data['correo'];
        $_SESSION['user_name'] = $user_data['nombre'];
        
        echo '<script>window.location = "../front/dashboard.php";</script>';
    } else {
        $message = urlencode("Usuario o contraseñas incorrectos");
        header("Location: ../index.php?status=error&message=$message");
    }
} catch (PDOException $e) {
    $message = urlencode("Error en el sistema: " . $e->getMessage());
    header("Location: ../index.php?status=error&message=$message");
}
?>