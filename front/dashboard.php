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
    $query = "SELECT puntos FROM usuarios WHERE correo = ?";
    
    // Preparar y ejecutar la consulta con MySQLi
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    
    // Obtener los puntos (o 0 si no hay datos)
    $puntos = $user_data ? number_format($user_data['puntos'], 0, ',', '.') : '0';

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
                <a href="profile.php" class="text-white hover:text-green-200">
                    <img src="media/user.png" width="100" alt="Perfil">
                </a>
                <a href="../index.php" class="text-white hover:text-green-200">
                    Cerrar sesión
                </a>
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