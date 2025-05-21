<?php
    // Iniciar sesión y verificar autenticación
    session_start();
    require_once '../config/conexion.php';
    
    if (!isset($_SESSION['user_email'])) {
        header("Location: ../index.php");
        exit();
    }

    $title = "Desechar residuos - GREENPATH VISIONS";
    
    // Procesar el escaneo si se recibió un código QR
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['qr_content'])) {
        $qr_content = trim($_POST['qr_content']);
        $user_email = $_SESSION['user_email'];
        
        try {
            // Obtener puntos actuales del usuario
            $query = "SELECT puntos FROM usuarios WHERE correo = :email";
            $stmt = $conexion->prepare($query);
            $stmt->bindParam(':email', $user_email);
            $stmt->execute();
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $current_points = $user_data ? $user_data['puntos'] : 0;
            $points_to_add = 10; // Puntos a añadir por cada escaneo
            
            // Actualizar puntos en la base de datos
            $update_query = "UPDATE usuarios SET puntos = puntos + :points WHERE correo = :email";
            $update_stmt = $conexion->prepare($update_query);
            $update_stmt->bindParam(':points', $points_to_add);
            $update_stmt->bindParam(':email', $user_email);
            $update_stmt->execute();
            
            // Devolver respuesta JSON
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => '¡Puntos añadidos correctamente!',
                'new_points' => $current_points + $points_to_add,
                'points_added' => $points_to_add
            ]);
            exit();
            
        } catch (PDOException $e) {
            error_log("Error al actualizar puntos: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Error al procesar los puntos'
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
    <script src="https://cdn.jsdelivr.net/npm/instascan@1.0.0/dist/instascan.min.js"></script>
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

    <main class="container mx-auto p-4 flex flex-col items-center">
        <div class="bg-white rounded-xl shadow-lg p-8 max-w-md w-full text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Desechar residuos</h2>
            
            <div class="bg-gray-100 p-6 rounded-lg mb-6">
                <div class="bg-white p-4 rounded">
                    <!-- Lector de cámara para QR -->
                    <video id="qr-scanner" width="100%" style="display: none;"></video>
                    <canvas id="qr-canvas" width="300" height="300" style="display: none;"></canvas>
                    <div id="scanner-container" class="w-full">
                        <p id="scanner-status" class="text-gray-500">Preparando cámara...</p>
                    </div>
                </div>
            </div>

            <p class="text-gray-700 mb-6">
                Escanea el código QR del contenedor de residuos
            </p>

            <div id="scan-result" class="hidden mb-4 p-3 bg-green-100 text-green-800 rounded"></div>

            <button id="start-scanner" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200">
                Activar cámara
            </button>
        </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
    const scannerButton = document.getElementById('start-scanner');
    const videoElement = document.getElementById('qr-scanner');
    const scannerStatus = document.getElementById('scanner-status');
    const scanResult = document.getElementById('scan-result');
    
    let isScanning = false;
    let scanner = null;
    let activeCamera = null;

    scannerButton.addEventListener('click', toggleScanner);

    function toggleScanner() {
        if (!isScanning) {
            startScanner();
        } else {
            stopScanner();
        }
    }

    async function startScanner() {
        try {
            scannerStatus.textContent = "Buscando cámaras...";
            scannerButton.disabled = true;
            
            // Cargar Instascan dinámicamente si no está disponible
            if (typeof Instascan === 'undefined') {
                await loadInstascan();
            }

            const cameras = await Instascan.Camera.getCameras();
            
            if (cameras.length === 0) {
                throw new Error("No se encontraron cámaras disponibles");
            }

            // Seleccionar cámara trasera o la primera disponible
            activeCamera = cameras.find(c => c.name.includes('back') || 
                           cameras.find(c => c.facingMode === 'environment') || 
                           cameras[0];

            scanner = new Instascan.Scanner({
                video: videoElement,
                mirror: false,
                scanPeriod: 5,
                backgroundScan: false
            });

            scanner.addListener('scan', handleScan);

            await scanner.start(activeCamera);
            
            videoElement.style.display = 'block';
            scannerStatus.textContent = "Escaneando...";
            scannerButton.textContent = "Detener cámara";
            scannerButton.disabled = false;
            isScanning = true;
            
        } catch (error) {
            console.error("Error al iniciar escáner:", error);
            scannerStatus.textContent = `Error: ${error.message}`;
            scannerButton.textContent = "Activar cámara";
            scannerButton.disabled = false;
            isScanning = false;
            
            // Mostrar mensaje específico para errores de permisos
            if (error.name === 'NotAllowedError') {
                scannerStatus.textContent = "Permiso de cámara denegado. Por favor habilita los permisos.";
            }
        }
    }

    function stopScanner() {
        if (scanner) {
            scanner.stop();
            scanner.removeListener('scan', handleScan);
        }
        
        videoElement.style.display = 'none';
        scannerStatus.textContent = "Cámara detenida";
        scannerButton.textContent = "Activar cámara";
        isScanning = false;
    }

    async function loadInstascan() {
        return new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/instascan@1.0.0/dist/instascan.min.js';
            script.onload = resolve;
            script.onerror = () => reject(new Error("No se pudo cargar Instascan"));
            document.head.appendChild(script);
        });
    }

    function handleScan(content) {
        scanResult.classList.remove('hidden');
        scanResult.textContent = `Escaneado: ${content}`;
        
        fetch('dispose.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `qr_content=${encodeURIComponent(content)}`
        })
        .then(handleResponse)
        .catch(handleError);
    }

    function handleResponse(response) {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    }

    function handleError(error) {
        console.error("Error:", error);
        scanResult.textContent = `Error: ${error.message}`;
        scanResult.className = 'mb-4 p-3 bg-red-100 text-red-800 rounded';
    }
});
</script>
</body>
</html>