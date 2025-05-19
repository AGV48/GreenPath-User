<?php
    $title = "Desechar residuos - GREENPATH VISIONS";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="../../usuarios/front/css/styles.css">
    <link rel="shortcut icon" href="../../usuarios/front/media/logo.png">
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
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
                <a href="../../usuarios/front/profile.php" class="text-white hover:text-green-200">
                    <img src="../../usuarios/front/media/user.png" width="100" alt="Perfil">
                </a>
                <a href="../../usuarios/index.php" class="text-white hover:text-green-200">
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
            const scannerContainer = document.getElementById('scanner-container');
            const scannerStatus = document.getElementById('scanner-status');
            const scanResult = document.getElementById('scan-result');
            
            let scanner = null;
            let isScanning = false;

            scannerButton.addEventListener('click', function() {
                if (!isScanning) {
                    startScanner();
                } else {
                    stopScanner();
                }
            });

            function startScanner() {
                scannerStatus.textContent = "Buscando cámara...";
                
                // Configurar el escáner
                scanner = new Instascan.Scanner({
                    video: videoElement,
                    mirror: false,
                    backgroundScan: true,
                    refractoryPeriod: 5000,
                    scanPeriod: 1
                });

                scanner.addListener('scan', function(content) {
                    scanResult.textContent = "Código QR escaneado: " + content;
                    scanResult.classList.remove('hidden');
                    
                    // Aquí puedes enviar el contenido del QR al servidor para procesarlo
                    // Por ejemplo con fetch() o AJAX
                    console.log("QR escaneado:", content);
                    
                    // Detener el escáner después de leer
                    stopScanner();
                });

                // Buscar cámaras disponibles
                Instascan.Camera.getCameras().then(function(cameras) {
                    if (cameras.length > 0) {
                        scanner.start(cameras[0]).then(function() {
                            videoElement.style.display = 'block';
                            scannerContainer.innerHTML = '';
                            scannerContainer.appendChild(videoElement);
                            scannerStatus.textContent = "Escaneando...";
                            scannerButton.textContent = "Detener cámara";
                            isScanning = true;
                        }).catch(function(e) {
                            console.error(e);
                            scannerStatus.textContent = "Error al iniciar la cámara: " + e;
                        });
                    } else {
                        scannerStatus.textContent = "No se encontraron cámaras disponibles";
                    }
                }).catch(function(e) {
                    console.error(e);
                    scannerStatus.textContent = "Error al acceder a la cámara: " + e;
                });
            }

            function stopScanner() {
                if (scanner) {
                    scanner.stop();
                }
                videoElement.style.display = 'none';
                scannerContainer.innerHTML = '<p id="scanner-status" class="text-gray-500">Cámara detenida</p>';
                scannerButton.textContent = "Activar cámara";
                isScanning = false;
            }
        });
    </script>
</body>
</html>