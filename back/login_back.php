<?php
    // se inicia una sesión
    session_start();
    
    // se llama el archivo conexion.php para conectarse a la base de datos
    include "../config/conexion.php";

    // se obtienen los datos del formulario de inicio de sesión
    $email = $_POST['email'];
    $password = $_POST['password'];

    // se verifica si los campos están vacíos
    if (empty($email) || empty($password)) {
        echo '
            <script>
                alert("Por favor, completa todos los campos");
                window.location = "../../usuarios/index.php";
            </script>';
        exit();
    }

    // se crea la consulta preparada para mayor seguridad
    $query = "SELECT nombre, correo, puntos FROM usuarios WHERE correo = ? AND contrasena = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "ss", $email, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // si el usuario y la contraseña son correctos
    if (mysqli_num_rows($result) > 0) {
        $user_data = mysqli_fetch_assoc($result);

        // Almacenar datos del usuario en la sesión
        $_SESSION['user_email'] = $user_data['correo'];
        $_SESSION['user_name'] = $user_data['nombre'];
        
        echo '
            <script>
                window.location = "../../usuarios/front/dashboard.php";
            </script>';
    } else {
        $message = urlencode("Usuario o contraseñas incorrectos");
        header("Location: ../../usuarios/index.php?status=error&message=$message");
        exit();
    }

    // Cerrar la conexión
    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
?>