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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="min-h-screen bg-gray-50">
    <header class="bg-gradient-to-r from-green-600 to-green-700 text-white p-4 shadow-lg sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <a href="dashboard.php" class="flex items-center gap-4 hover:opacity-90 transition-opacity">
                <img src="media/logo.png" width="50" height="50" alt="Logo de GREENPATH" class="rounded-lg shadow-sm">
                <div>
                    <h1 class="text-2xl font-bold">GREENPATH</h1>
                    <h2 class="text-xl">VISIONS</h2>
                </div>
            </a>

            <nav class="flex items-center space-x-6">
                <div class="flex items-center space-x-4">
                    <a href="dashboard.php" class="flex items-center px-3 py-2 rounded-lg hover:bg-green-500 transition-colors">
                        <i class="fas fa-home mr-2"></i>
                        <span>Inicio</span>
                    </a>
                    
                    <div class="relative group">
                        <button class="flex items-center px-3 py-2 rounded-lg hover:bg-green-500 transition-colors">
                            <i class="fas fa-user mr-2"></i>
                            <span><?php echo htmlspecialchars($user_name); ?></span>
                            <i class="fas fa-chevron-down ml-2 text-xs"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block">
                            <a href="profile.php" class="block px-4 py-2 text-gray-800 hover:bg-green-100 transition-colors">
                                <i class="fas fa-user-circle mr-2"></i> Mi perfil
                            </a>
                            <a href="profile_edit.php" class="block px-4 py-2 text-gray-800 hover:bg-green-100 transition-colors">
                                <i class="fas fa-cog mr-2"></i> Configuración
                            </a>
                            <a href="change_password.php" class="block px-4 py-2 text-gray-800 hover:bg-green-100 transition-colors">
                                <i class="fas fa-key mr-2"></i> Cambiar contraseña
                            </a>
                            <div class="border-t border-gray-200 my-1"></div>
                            <a href="../index.php" class="block px-4 py-2 text-red-600 hover:bg-red-50 transition-colors">
                                <i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión
                            </a>
                        </div>
                    </div>
                    
                    <div class="flex items-center px-3 py-2 rounded-lg bg-green-500">
                        <i class="fas fa-coins mr-2"></i>
                        <span class="font-bold"><?php echo $user_points; ?> pts</span>
                    </div>
                </div>
            </nav>
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