<?php
    include "../../usuarios/config/conexion.php";

    // se obtienen los datos del formulario de registro
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // se verifica si los campos están vacíos
    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
        $message = urlencode("Por favor, completa todos los campos");
        header("Location: ../../usuarios/front/register.php?status=error&message=$message");
        exit();
    }

    // se verifica si la contraseña y la confirmación de contraseña son iguales
    if ($password !== $confirmPassword) {
        $message = urlencode("Las contraseñas no coinciden");
        header("Location: ../../usuarios/front/register.php?status=error&message=$message");
        exit();
    }

    // se verifica si la contraseña tiene al menos 8 caracteres
    if (strlen($password) < 8) {
        $message = urlencode("La contraseña debe tener al menos 8 caracteres");
        header("Location: ../../usuarios/front/register.php?status=error&message=$message");
        exit();
    }

    // se verifica si el email es de gmail, hotmail u outlook
    if (!str_contains($email, "@gmail.com") && !str_contains($email, "@hotmail.com") && !str_contains($email, "@outlook.com")) {
        $message = urlencode("Email inválido. Solo se permiten cuentas de Gmail, Hotmail u Outlook");
        header("Location: ../../usuarios/front/register.php?status=error&message=$message");
        exit();
    }

    // se verifica si el email ya existe en la base de datos
    $verificar_email = mysqli_query($conexion, "SELECT * FROM usuarios WHERE correo = '$email'");
    if (mysqli_num_rows($verificar_email) > 0) {
        $message = urlencode("Ya existe un usuario con ese email");
        header("Location: ../../usuarios/front/register.php?status=error&message=$message");
        exit();
    }

    // se crea la consulta para insertar los datos en la base de datos
    $query = "INSERT INTO usuarios (nombre, correo, contrasena, puntos) VALUES ('$name', '$email', '$password', 0)";

    // se ejecuta la consulta
    $registro = mysqli_query($conexion, $query);

    // se verifica si se ejecutó correctamente la consulta
    if (!$registro) {
        $message = urlencode("Error al registrarse. Por favor, inténtalo de nuevo");
        header("Location: ../../usuarios/front/register.php?status=error&message=$message");
        exit();
    } else {
        $message = urlencode("Usuario registrado exitosamente");
        header("Location: ../../usuarios/front/register.php?status=success&message=$message");
        exit();
    }

    mysqli_close($conexion);
?>