<?php
session_start();
include "../config/conexion.php";

if (!isset($_SESSION['user_email'])) {
    header("Location: ../index.php");
    exit();
}

$old_email = $_SESSION['user_email'];
$name = $_POST['name'];
$email = $_POST['email'];

if (empty($name) || empty($email)) {
    $message = urlencode("Por favor, completa todos los campos");
    header("Location: ../front/profile_edit.php?status=error&message=$message");
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $message = urlencode("El formato del email no es válido");
    header("Location: ../front/profile_edit.php?status=error&message=$message");
    exit();
}

try {
    if ($email !== $old_email) {
        $check_email = $conexion->prepare("SELECT correo FROM usuarios WHERE correo = :email");
        $check_email->bindParam(':email', $email);
        $check_email->execute();
        
        if ($check_email->rowCount() > 0) {
            $message = urlencode("El email ya está en uso por otro usuario");
            header("Location: ../front/profile_edit.php?status=error&message=$message");
            exit();
        }
    }

    $stmt = $conexion->prepare("UPDATE usuarios SET nombre = :name, correo = :new_email WHERE correo = :old_email");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':new_email', $email);
    $stmt->bindParam(':old_email', $old_email);
    
    if ($stmt->execute()) {
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        
        $message = urlencode("Perfil actualizado correctamente");
        header("Location: ../front/profile_edit.php?status=success&message=$message");
    } else {
        $message = urlencode("Error al actualizar el perfil");
        header("Location: ../front/profile_edit.php?status=error&message=$message");
    }
} catch (PDOException $e) {
    $message = urlencode("Error en el sistema: " . $e->getMessage());
    header("Location: ../front/profile_edit.php?status=error&message=$message");
}
?>