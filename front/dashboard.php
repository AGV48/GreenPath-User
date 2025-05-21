<?php
    // Se inicializa la sesión
    session_start();
    
    // Se incluye el archivo de conexión a la base de datos
    require_once '../config/conexion.php';

    // Verificar si el usuario está logueado
    if (!isset($_SESSION['user_email'])) {
        header("Location: ../index.php");
        exit();
    }

    // Se obtienen los puntos del usuario desde la base de datos
    $user_email = $_SESSION['user_email'];
    
    try {
        $query = "SELECT puntos, nombre FROM usuarios WHERE correo = :email";
        $stmt = $conexion->prepare($query);
        $stmt->bindParam(':email', $user_email);
        $stmt->execute();
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Obtener los puntos (o 0 si no hay datos)
        $puntos = $user_data ? number_format($user_data['puntos'], 0, ',', '.') : '0';
        $nombre_usuario = $user_data['nombre'] ?? 'Usuario';
    } catch (PDOException $e) {
        // Manejo de errores
        error_log("Error al obtener datos del usuario: " . $e->getMessage());
        $puntos = '0';
        $nombre_usuario = 'Usuario';
    }

    $title = "Dashboard - GREENPATH VISIONS";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="shortcut icon" href="media/logo.png">
    <!-- Iconos de Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="min-h-screen bg-gray-50">
    <header class="bg-gradient-to-r from-green-600 to-green-700 text-white p-4 shadow-lg sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <!-- Logo y marca -->
            <div class="flex items-center gap-4">
                <a href="dashboard.php" class="flex items-center space-x-2 gap-4 hover:opacity-90 transition-opacity">
                    <img src="media/logo.png" width="50" height="50" alt="Logo de GREENPATH" class="rounded-lg shadow-sm">
                    <div>
                        <h1 class="text-2xl font-bold">GREENPATH</h1>
                        <h2 class="text-xl">VISIONS</h2>
                    </div>
                </a>
            </div>

            <!-- Menú de navegación -->
            <nav class="flex items-center space-x-6">
                <div class="flex items-center space-x-4">
                    <a href="dashboard.php" class="flex items-center px-3 py-2 rounded-lg hover:bg-green-500 transition-colors">
                        <i class="fas fa-home mr-2"></i>
                        <span>Inicio</span>
                    </a>
                    
                    <div class="relative group">
                        <button class="flex items-center px-3 py-2 rounded-lg hover:bg-green-500 transition-colors">
                            <i class="fas fa-user mr-2"></i>
                            <span><?php echo htmlspecialchars($nombre_usuario); ?></span>
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
                        <span class="font-bold"><?php echo $puntos; ?> pts</span>
                    </div>
                </div>
            </nav>
        </div>
    </header>


    <main class="container mx-auto p-4">
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Tus puntos</h3>
                <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full font-bold">
                    <?php echo $puntos; ?> puntos
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-blue-100 text-blue-800 rounded-xl shadow-sm p-6 hover:shadow-md transition cursor-pointer">
                <div class="flex items-center space-x-3">
                    <span class="text-2xl">🎁</span>
                    <h3 class="text-lg font-semibold">Bonos</h3>
                </div>
            </div>
            
            <a href="dispose.php" class="bg-green-100 text-green-800 rounded-xl shadow-sm p-6 hover:shadow-md transition cursor-pointer">
                <div class="flex items-center space-x-3">
                    <span class="text-2xl">♻️</span>
                    <h3 class="text-lg font-semibold">Desecha</h3>
                </div>
            </a>
            
            <div class="bg-yellow-100 text-yellow-800 rounded-xl shadow-sm p-6 hover:shadow-md transition cursor-pointer">
                <div class="flex items-center space-x-3">
                    <span class="text-2xl">💡</span>
                    <h3 class="text-lg font-semibold">Consejos</h3>
                </div>
            </div>
            
            <div class="md:col-span-3 bg-purple-100 text-purple-800 rounded-xl shadow-sm p-6 hover:shadow-md transition cursor-pointer">
                <div class="flex items-center space-x-3">
                    <span class="text-2xl">📊</span>
                    <h3 class="text-lg font-semibold">Estadísticas</h3>
                </div>
            </div>
        </div>
    </main>
</body>
</html>