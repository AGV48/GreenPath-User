<?php
    // se inicia la sesión
    session_start();
    // Se incluye el archivo de conexión a la base de datos
    include "../config/conexion.php";

    // Verificar si el usuario está logueado
    if (!isset($_SESSION['user_email'])) {
        header("Location: ../index.php");
        exit();
    }

    // Obtener datos antiguos y nuevos
    $old_email = $_SESSION['user_email'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Validar campos vacíos
    if (empty($name) || empty($email)) {
        $message = urlencode("Por favor, completa todos los campos");
        header("Location: ../front/profile_edit.php?status=error&message=$message");
        exit();
    }

    // Validar formato de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = urlencode("El formato del email no es válido");
        header("Location: ../front/profile_edit.php?status=error&message=$message");
        exit();
    }

    // Verificar si el nuevo email ya existe (excepto para el usuario actual)
    if ($email !== $old_email) {
        $check_email = $conexion->prepare("SELECT correo FROM usuarios WHERE correo = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $result = $check_email->get_result();
        
        if ($result->num_rows > 0) {
            $message = urlencode("El email ya está en uso por otro usuario");
            header("Location: ../front/profile_edit.php?status=error&message=$message");
            exit();
        }
    }

    // Actualizar datos en la base de datos
    $stmt = $conexion->prepare("UPDATE usuarios SET nombre = ?, correo = ? WHERE correo = ?");
    $stmt->bind_param("sss", $name, $email, $old_email);
    
    if ($stmt->execute()) {
        // Actualizar datos de sesión
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        
        $message = urlencode("Perfil actualizado correctamente");
        header("Location: ../front/profile_edit.php?status=success&message=$message");
    } else {
        $message = urlencode("Error al actualizar el perfil");
        header("Location: ../front/profile_edit.php?status=error&message=$message");
    }

    $stmt->close();
    $conexion->close();
?>