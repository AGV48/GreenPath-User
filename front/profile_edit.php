<?php
    // Iniciar sesión y verificar autenticación
    session_start();

    // Se incluye el archivo de conexión a la base de datos
    require_once '../config/conexion.php';

    // Verificar si el usuario está logueado
    if (!isset($_SESSION['user_email'])) {
        header("Location: ../index.php");
        exit();
    }

    // Obtener datos de la sesión
    $user_name = $_SESSION['user_name'];
    $user_email = $_SESSION['user_email'];

    $title = "Editar Perfil - GREENPATH VISIONS";
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
<body class="min-h-screen bg-gray-50">
    <header class="bg-green-600 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="javascript:history.back()" class="btn-back">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fillRule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clipRule="evenodd" />
                    </svg>
                    Volver
                </a>
                <div class="flex items-center space-x-2 gap-4">
                    <img src="media/logo.png" width="50" height="50" alt="Logo de GREENPATH">
                    <div>
                        <h1 class="text-2xl font-bold">GREENPATH</h1>
                        <h2 class="text-xl">VISIONS</h2>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="container mx-auto p-4 flex justify-center items-center">
    <div class="max-w-md w-full bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="bg-green-600 p-6 text-white text-center">
                <h2 class="text-xl font-bold">Editar perfil</h2>
            </div>

            <form id="editForm" action="../back/profile_edit_back.php" method="POST" class="p-6 space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre completo
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="<?php echo htmlspecialchars($user_name); ?>"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
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
                        value="<?php echo htmlspecialchars($user_email); ?>"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                        required
                    >
                </div>

                <button
                    type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 mt-4"
                >
                    Guardar cambios
                </button>
            </form>
        </div>
    </main>

    <script>
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
                    });
                }
            }
        });
    </script>
</body>
</html>