<?php
session_start();
include "../config/conexion.php";

if (!isset($_SESSION['user_email'])) {
    header("Location: ../index.php");
    exit();
}

$email = $_SESSION['user_email'];
$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
    $message = urlencode("Por favor, completa todos los campos");
    header("Location: ../front/change_password.php?status=error&message=$message");
    exit();
}

if ($new_password !== $confirm_password) {
    $message = urlencode("Las nuevas contraseñas no coinciden");
    header("Location: ../front/change_password.php?status=error&message=$message");
    exit();
}

if (strlen($new_password) < 8) {
    $message = urlencode("La nueva contraseña debe tener al menos 8 caracteres");
    header("Location: ../front/change_password.php?status=error&message=$message");
    exit();
}

try {
    $stmt = $conexion->prepare("SELECT contrasena FROM usuarios WHERE correo = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user || $user['contrasena'] !== $current_password) {
        $message = urlencode("La contraseña actual es incorrecta");
        header("Location: ../front/change_password.php?status=error&message=$message");
        exit();
    }

    $update_stmt = $conexion->prepare("UPDATE usuarios SET contrasena = :new_password WHERE correo = :email");
    $update_stmt->bindParam(':new_password', $new_password);
    $update_stmt->bindParam(':email', $email);
    
    if ($update_stmt->execute()) {
        $message = urlencode("Contraseña cambiada exitosamente");
        header("Location: ../front/change_password.php?status=success&message=$message");
    } else {
        $message = urlencode("Error al cambiar la contraseña");
        header("Location: ../front/change_password.php?status=error&message=$message");
    }
} catch (PDOException $e) {
    $message = urlencode("Error en el sistema: " . $e->getMessage());
    header("Location: ../front/change_password.php?status=error&message=$message");
}
?>