<?php
    // Iniciar sesión y verificar autenticación
    session_start();

    // Se incluye el archivo de conexión a la base de datos
    require_once '../config/conexion.php';

    if (!isset($_SESSION['user_email'])) {
        header("Location: ../index.php");
        exit();
    }

    // Obtener datos de la sesión
    $user_name = $_SESSION['user_name'];
    $user_email = $_SESSION['user_email'];

    try {
        $query = "SELECT puntos FROM usuarios WHERE correo = :email";
        $stmt = $conexion->prepare($query);
        $stmt->bindParam(':email', $user_email);
        $stmt->execute();
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Obtener los puntos (o 0 si no hay datos)
        $user_points = $user_data ? number_format($user_data['puntos'], 0, ',', '.') : '0';
    } catch (PDOException $e) {
        // Manejo de errores
        error_log("Error al obtener puntos de usuario: " . $e->getMessage());
        $user_points = '0';
    }

    $title = "Perfil - GREENPATH VISIONS";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="shortcut icon" href="media/logo.png">
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

            <nav class="flex items-center space-x-4">
                <a href="dashboard.php" class="text-white hover:text-green-200">
                    Inicio
                </a>
                <a href="../index.php" class="text-white hover:text-green-200">
                    Cerrar sesión
                </a>
            </nav>
        </div>
    </header>

    <main class="container mx-auto p-4">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="bg-green-600 p-6 text-white">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-green-600 text-2xl font-bold">
                        <?php echo strtoupper(substr($user_name, 0, 1)); ?>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold"><?php echo htmlspecialchars($user_name); ?></h2>
                        <p class="text-green-100"><?php echo htmlspecialchars($user_email); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Información de cuenta</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Nombre:</span>
                        <span class="font-medium"><?php echo htmlspecialchars($user_name); ?></span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Correo electrónico:</span>
                        <span class="font-medium"><?php echo htmlspecialchars($user_email); ?></span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Puntos acumulados:</span>
                        <span class="font-medium text-green-600"><?php echo $user_points; ?> puntos</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="divide-y divide-gray-200">
                <a href="profile_edit.php" class="block p-4 hover:bg-gray-50 transition">
                    <div class="flex items-center space-x-3">
                        <span class="text-gray-500">✏️</span>
                        <span class="font-medium">Editar datos</span>
                    </div>
                </a>
                
                <a href="change_password.php" class="block p-4 hover:bg-gray-50 transition">
                    <div class="flex items-center space-x-3">
                        <span class="text-gray-500">🔒</span>
                        <span class="font-medium">Cambiar contraseña</span>
                    </div>
                </a>
            </div>
        </div>
    </main>
</body>
</html>