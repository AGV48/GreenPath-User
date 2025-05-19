<?php
    // Iniciar sesión y verificar autenticación
    session_start();

    // Se incluye el archivo de conexión a la base de datos
    require_once '../../usuarios/config/conexion.php';

    if (!isset($_SESSION['user_email'])) {
        header("Location: ../../usuarios/index.php");
        exit();
    }

    // Obtener datos de la sesión
    $user_name = $_SESSION['user_name'];
    $user_email = $_SESSION['user_email'];

    $query = "SELECT puntos FROM usuarios WHERE correo = ?";
    
    // Preparar y ejecutar la consulta con MySQLi
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    
    // Obtener los puntos (o 0 si no hay datos)
    $user_points = $user_data ? number_format($user_data['puntos'], 0, ',', '.') : '0';

    $title = "Perfil - GREENPATH VISIONS";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="../../usuarios/front/css/styles.css">
    <link rel="shortcut icon" href="../../usuarios/front/media/logo.png">
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
                <div>
                    <h1 class="text-2xl font-bold">GREENPATH</h1>
                    <h2 class="text-xl">VISIONS</h2>
                </div>
            </div>

            <nav class="flex items-center space-x-4">
                <a href="../../usuarios/front/dashboard.php" class="text-white hover:text-green-200">
                    Inicio
                </a>
                <a href="../../usuarios/index.php" class="text-white hover:text-green-200">
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
                <a href="../../usuarios/front/profile_edit.php" class="block p-4 hover:bg-gray-50 transition">
                    <div class="flex items-center space-x-3">
                        <span class="text-gray-500">✏️</span>
                        <span class="font-medium">Editar datos</span>
                    </div>
                </a>
                
                <a href="../../usuarios/front/change_password.php" class="block p-4 hover:bg-gray-50 transition">
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