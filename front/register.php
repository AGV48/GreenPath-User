<?php
    $title = "Registro - GREENPATH VISIONS";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="shortcut icon" href="media/logo.png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="min-h-screen bg-gradient-to-b from-green-50 to-green-100 flex flex-col items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
        <div class="text-center mb-8">
            <img src="media/logo.png" width="100" height="100" alt="Logo de GREENPATH">
            <h1 class="text-4xl font-bold text-green-600 mb-2">GREENPATH</h1>
            <h2 class="text-2xl font-semibold text-gray-700">VISIONS</h2>
            <p class="text-gray-600 mt-2"><b>Crea tu cuenta</b></p>
        </div>

        <form id="registerForm" action="../back/register_back.php" class="space-y-4" method="POST">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                    Nombre completo
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                    placeholder="Tu nombre"
                    required
                >
            </div>

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
                    minlength="8"
                >
                <p class="text-xs text-gray-500 mt-1">Mínimo 8 caracteres</p>
            </div>

            <div>
                <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-1">
                    Confirmar contraseña
                </label>
                <input
                    type="password"
                    id="confirmPassword"
                    name="confirmPassword"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                    placeholder="••••••••"
                    required
                    minlength="8"
                >
            </div>

            <div class="flex items-center">
                <input
                    type="checkbox"
                    id="terms"
                    name="terms"
                    class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                    required
                >
                <label for="terms" class="ml-2 block text-sm text-gray-700">
                    Acepto los <a href="#" class="text-green-600 hover:text-green-800">términos y condiciones</a>
                </label>
            </div>

            <button
                type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 mt-4"
            >
                Crear cuenta
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-gray-600">
                ¿Ya tienes una cuenta? <a href="../index.php" class="text-green-600 hover:text-green-800 font-medium">Iniciar sesión</a>
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
                if (status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: decodedMessage,
                        confirmButtonColor: '#16a34a',
                    }).then(() => {
                        window.location.href = "../index.php";
                    });
                } else if (status === 'error') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: decodedMessage,
                        confirmButtonColor: '#16a34a',
                    }).then(() => {
                        window.location.href = "register.php";
                    });
                }
            }
        });
    </script>
</body>
</html>