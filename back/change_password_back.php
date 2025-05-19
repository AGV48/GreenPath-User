<?php
    session_start();
    include "../../usuarios/config/conexion.php";

    if (!isset($_SESSION['user_email'])) {
        header("Location: ../../usuarios/index.php");
        exit();
    }

    $email = $_SESSION['user_email'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validar campos vacíos
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $message = urlencode("Por favor, completa todos los campos");
        header("Location: ../../usuarios/front/change_password.php?status=error&message=$message");
        exit();
    }

    // Validar que las nuevas contraseñas coincidan
    if ($new_password !== $confirm_password) {
        $message = urlencode("Las nuevas contraseñas no coinciden");
        header("Location: ../../usuarios/front/change_password.php?status=error&message=$message");
        exit();
    }

    // Validar longitud mínima
    if (strlen($new_password) < 8) {
        $message = urlencode("La nueva contraseña debe tener al menos 8 caracteres");
        header("Location: ../../usuarios/front/change_password.php?status=error&message=$message");
        exit();
    }

    // Verificar contraseña actual
    $stmt = $conexion->prepare("SELECT contrasena FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if (!$user || $user['contrasena'] !== $current_password) {
        $message = urlencode("La contraseña actual es incorrecta");
        header("Location: ../../usuarios/front/change_password.php?status=error&message=$message");
        exit();
    }

    // Actualizar contraseña
    $update_stmt = $conexion->prepare("UPDATE usuarios SET contrasena = ? WHERE correo = ?");
    $update_stmt->bind_param("ss", $new_password, $email);
    
    if ($update_stmt->execute()) {
        $message = urlencode("Contraseña cambiada exitosamente");
        header("Location: ../../usuarios/front/change_password.php?status=success&message=$message");
    } else {
        $message = urlencode("Error al cambiar la contraseña");
        header("Location: ../../usuarios/front/change_password.php?status=error&message=$message");
    }

    $stmt->close();
    $update_stmt->close();
    $conexion->close();
?>