<?php
    // Se inicializa la sesión
    session_start();

    // Se incluye el archivo de conexión a la base de datos
    require_once 'config/conexion.php';

    $title = "Inicio de sesión - GREENPATH VISIONS";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="front/css/styles.css">
    <link rel="shortcut icon" href="front/media/logo.png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="min-h-screen bg-gradient-to-b from-green-50 to-green-100 flex flex-col items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
        <div class="text-center mb-8">
            <img src="front/media/logo.png" width="100" height="100" alt="Logo de GREENPATH">
            <h1 class="text-4xl font-bold text-green-600 mb-2">GREENPATH</h1>
            <h2 class="text-2xl font-semibold text-gray-700">VISIONS</h2>
        </div>

        <form class="space-y-6" action="back/login_back.php" method="POST">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                    Correo electrónico
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                    placeholder="tu@email.com"
                    required
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                    Contraseña
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                    placeholder="••••••••"
                    required
                >
            </div>

            <br>

            <button
                type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200"
            >
                Iniciar sesión
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-gray-600">
                ¿No tienes una cuenta? <a href="front/register.php" class="text-green-600 hover:text-green-800 font-medium">Crear cuenta</a>
            </p>
        </div>
    </div>

    <script>
        // Manejo de parámetros de URL para mostrar alertas
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
            const message = urlParams.get('message');

            if (status && message) {
                const decodedMessage = decodeURIComponent(message);
                if (status === 'error') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: decodedMessage,
                        confirmButtonColor: '#16a34a',
                    }).then(() => {
                        window.location.href = "../index.php";
                    });
                }
            }
        });
    </script>
</body>
</html>