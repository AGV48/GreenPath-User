<?php
    // Iniciar sesión y verificar autenticación
    session_start();
    require_once '../config/conexion.php';
    
    if (!isset($_SESSION['user_email'])) {
        header("Location: ../index.php");
        exit();
    }

    $title = "Desechar residuos - GREENPATH VISIONS";
    
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

    // Procesar el escaneo si se recibió un código QR
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['qr_content'])) {
    $qr_content = $_POST['qr_content'];
    $user_email = $_SESSION['user_email'];
    
    try {
        // Decodificar el contenido del QR
        $qr_data = json_decode($qr_content, true);
        
        // Validar que sea un QR válido de GREENPATH
        if (!$qr_data || !isset($qr_data['system']) || $qr_data['system'] !== 'GREENPATH') {
            throw new Exception("Código QR no válido");
        }
        
        // Verificar si este QR ya fue escaneado por el usuario
        $check_query = "SELECT COUNT(*) as count FROM qr_escaneados 
               WHERE usuario_email = :email AND qr_id = :qr_id";
        $check_stmt = $conexion->prepare($check_query);
        $check_stmt->bindParam(':email', $user_email);
        $check_stmt->bindParam(':qr_id', $qr_data['waste_id']);
        $check_stmt->execute();
        $result = $check_stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] > 0) {
            throw new Exception("Ya has escaneado este código QR antes");
        }
        
        // Resto del código para otorgar puntos...
        $points_to_add = 10; // Puntos por desecho correcto
        
        // Iniciar transacción
        $conexion->beginTransaction();
        
        // Actualizar puntos
        $update_query = "UPDATE usuarios SET puntos = puntos + :points WHERE correo = :email";
        $update_stmt = $conexion->prepare($update_query);
        $update_stmt->bindParam(':points', $points_to_add);
        $update_stmt->bindParam(':email', $user_email);
        $update_stmt->execute();
        
        // Registrar el QR escaneado
        $register_query = "INSERT INTO qr_escaneados (usuario_email, qr_id, waste_type, points_earned, scan_date)
                  VALUES (:email, :qr_id, :waste_type, :points, NOW())";
        $register_stmt = $conexion->prepare($register_query);
        $register_stmt->bindParam(':email', $user_email);
        $register_stmt->bindParam(':qr_id', $qr_data['waste_id']);
        $register_stmt->bindParam(':waste_type', $qr_data['waste_type']);
        $register_stmt->bindParam(':points', $points_to_add);
        $register_stmt->execute();
        
        $conexion->commit();
        
        // Respuesta JSON
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => '¡Puntos añadidos por desechar ' . $qr_data['waste_name'] . ' en la caneca ' . $qr_data['bin_color'] . '!',
            'new_points' => $current_points + $points_to_add,
            'points_added' => $points_to_add,
            'waste_name' => $qr_data['waste_name'],
            'bin_color' => $qr_data['bin_color']
        ]);
        exit();
        
    } catch (Exception $e) {
        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
        exit();
    }
}
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
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <style>
        
    </style>
</head>
<body class="min-h-screen bg-gray-50">
    <header class="header-container text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="dashboard.php" class="flex items-center gap-4 hover:opacity-90 transition-opacity">
                <img src="media/logo.png" width="50" height="50" alt="Logo de GREENPATH" class="rounded-lg">
                <div>
                    <h1 class="text-2xl font-bold">GREENPATH</h1>
                    <h2 class="text-xl">VISIONS</h2>
                </div>
            </a>

            <nav class="flex items-center gap-6">
                <a href="dashboard.php" class="nav-link flex items-center">
                    <i class="fas fa-home mr-2"></i>
                    <span>Inicio</span>
                </a>
                
                <div class="user-dropdown">
                    <button class="nav-link flex items-center">
                        <i class="fas fa-user-circle mr-2"></i>
                        <span><?php echo htmlspecialchars($user_name); ?></span>
                        <i class="fas fa-chevron-down ml-2 text-xs"></i>
                    </button>
                    <div class="user-dropdown-content">
                        <a href="profile.php">
                            <i class="fas fa-user"></i> Mi perfil
                        </a>
                        <a href="profile_edit.php">
                            <i class="fas fa-cog"></i> Configuración
                        </a>
                        <a href="change_password.php">
                            <i class="fas fa-key"></i> Cambiar contraseña
                        </a>
                        <div style="border-top: 1px solid #e5e7eb; margin: 0.25rem 0;"></div>
                        <a href="../index.php" style="color: #dc2626;">
                            <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                        </a>
                    </div>
                </div>
                
                <div class="points-display">
                    <i class="fas fa-coins mr-2"></i>
                    <span class="font-bold"><?php echo $user_points; ?> pts</span>
                </div>
            </nav>
        </div>
    </header>

    <main class="container mx-auto p-4 flex flex-col items-center">
        <div class="bg-white rounded-xl shadow-lg p-8 max-w-md w-full text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Desechar residuos</h2>
            
            <div class="bg-gray-100 p-6 rounded-lg mb-6">
                <div class="bg-white p-4 rounded">
                    <!-- Lector de cámara para QR -->
                    <div class="scanner-overlay">
                        <video id="qr-scanner" width="100%" style="display: none;"></video>
                    </div>
                    <div id="scanner-container" class="w-full">
                        <p id="scanner-status" class="text-gray-500">Preparando cámara...</p>
                    </div>
                </div>
            </div>

            <p class="text-gray-700 mb-6">
                Escanea el código QR del contenedor de residuos para obtener puntos
            </p>

            <div id="scan-result" class="hidden mb-4 p-3 rounded"></div>

            <button id="start-scanner" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200">
                Activar cámara
            </button>
        </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const scannerButton = document.getElementById('start-scanner');
        const videoElement = document.getElementById('qr-scanner');
        const scannerContainer = document.getElementById('scanner-container');
        const scannerStatus = document.getElementById('scanner-status');
        const scanResult = document.getElementById('scan-result');
        
        let isScanning = false;
        let scanner = null; // Declarar scanner aquí
        
        scannerButton.addEventListener('click', function() {
            if (!isScanning) {
                startScanner();
            } else {
                stopScanner();
            }
        });

        function startScanner() {
            scannerStatus.textContent = "Buscando cámara...";
            
            Instascan.Camera.getCameras().then(function(cameras) {
                if (cameras.length > 0) {
                    // Preferir cámara trasera
                    let selectedCamera = cameras[0];
                    if (cameras.length > 1) {
                        selectedCamera = cameras.find(cam => cam.name.includes('back') || cam.name.includes('rear')) || cameras[1];
                    }
                    
                    scanner = new Instascan.Scanner({
                        video: videoElement,
                        mirror: false,
                        scanPeriod: 5 // Escanear cada 5 segundos para mejor rendimiento
                    });
                    
                    scanner.start(selectedCamera).then(function() {
                        videoElement.style.display = 'block';
                        scannerStatus.textContent = "Escaneando código QR...";
                        scannerButton.textContent = "Detener cámara";
                        isScanning = true;
                    }).catch(function(err) {
                        console.error("Error al iniciar cámara:", err);
                        scannerStatus.textContent = "Error al iniciar la cámara";
                    });
                } else {
                    scannerStatus.textContent = "No se encontraron cámaras disponibles";
                }
            }).catch(function(err) {
                console.error("Error al acceder a cámaras:", err);
                scannerStatus.textContent = "Error al acceder a la cámara";
            });
        }

        function stopScanner() {
            if (scanner) {
                scanner.stop();
                videoElement.style.display = 'none';
                scannerButton.textContent = "Activar cámara";
                scannerStatus.textContent = "Cámara detenida";
                isScanning = false;
            }
        }

        // Escuchar el evento de escaneo
        if (scanner) {
            scanner.addListener('scan', function(content) {
                scanResult.classList.remove('hidden');
                scanResult.textContent = "Procesando código QR...";
                scanResult.className = "mb-4 p-3 bg-blue-100 text-blue-800 rounded";
                
                // Enviar el contenido del QR al servidor
                fetch('dispose.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'qr_content=' + encodeURIComponent(content)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        scanResult.textContent = data.message + " ¡+" + data.points_added + " puntos! Total: " + data.new_points + " puntos.";
                        scanResult.className = "mb-4 p-3 bg-green-100 text-green-800 rounded";
                        
                        // Actualizar el contador de puntos en el header
                        const pointsDisplay = document.querySelector('.points-display span');
                        if (pointsDisplay) {
                            pointsDisplay.textContent = data.new_points.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " pts";
                        }
                    } else {
                        scanResult.textContent = "Error: " + data.message;
                        scanResult.className = "mb-4 p-3 bg-red-100 text-red-800 rounded";
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    scanResult.textContent = "Error al conectar con el servidor";
                    scanResult.className = "mb-4 p-3 bg-red-100 text-red-800 rounded";
                });
            });
        }
    });
    </script>
</body>
</html>