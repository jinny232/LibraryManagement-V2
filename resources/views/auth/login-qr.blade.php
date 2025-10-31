<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Custom styles for the video and canvas to make them look better */
        #video,
        #canvas {
            border-radius: 0.75rem;
            width: 100%;
            height: auto;
            max-width: 300px;
            aspect-ratio: 1 / 1;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 2px solid #e5e7eb;
        }
    </style>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
    <!-- Main Login Card -->
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md text-center">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">Login</h1>
        <p class="text-gray-500 mb-6">Scan your QR code or enter it manually.</p>

        <!-- Error Message Box -->
        <div id="error-message-box" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative mb-4" role="alert">
            <span class="block sm:inline" id="error-message"></span>
        </div>

        <form id="login-form" method="POST" action="{{ route('login.qr.submit') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="qr_value" id="qr_value">

            <!-- Button to toggle the scanner -->
            <button type="button" id="toggleScanBtn" class="w-full flex items-center justify-center py-3 px-6 text-base font-semibold rounded-full text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200 shadow-md">
                <span id="scan-button-text">Start QR Code Scan</span>
            </button>

            <!-- QR Scanner Section (hidden by default) -->
            <div id="scanner-section" class="hidden flex flex-col items-center space-y-4 pt-4">
                <div class="relative w-full max-w-xs">
                    <video id="video" class="hidden"></video>
                    <canvas id="canvas" class="hidden"></canvas>
                    <div id="video-placeholder" class="bg-gray-200 rounded-xl flex items-center justify-center text-gray-500 text-sm h-64 w-64 mx-auto">
                        Camera preview will appear here.
                    </div>
                </div>
            </div>

            <!-- Separator -->
            <div class="relative flex py-5 items-center">
                <div class="flex-grow border-t border-gray-300"></div>
                <span class="flex-shrink mx-4 text-gray-400">OR</span>
                <div class="flex-grow border-t border-gray-300"></div>
            </div>

            <!-- Manual Input Section -->
            <div id="manual-input" class="space-y-3">
                <label for="manualQr" class="text-sm font-medium text-gray-600 block text-left">
                    Enter QR code manually
                </label>
                <input type="text" id="manualQr" placeholder="Enter QR code value here" autocomplete="off" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                <button type="button" id="manualSubmit" class="w-full py-3 px-6 text-base font-semibold rounded-full text-blue-600 border-2 border-blue-600 bg-white hover:bg-blue-50 transition-colors duration-200 shadow-sm">
                    Login with Manual Input
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <script>
        // Custom modal-like message box
        function showMessage(message) {
            const errorBox = document.getElementById('error-message-box');
            const errorMessage = document.getElementById('error-message');
            errorMessage.textContent = message;
            errorBox.classList.remove('hidden');
        }

        function hideMessage() {
            const errorBox = document.getElementById('error-message-box');
            errorBox.classList.add('hidden');
        }

        // Check for Laravel validation errors and display them
        @if ($errors->has('qr_value'))
            showMessage("{{ $errors->first('qr_value') }}");
        @endif

        const video = document.getElementById('video');
        const canvasElement = document.getElementById('canvas');
        const canvas = canvasElement.getContext('2d');
        const qrInput = document.getElementById('qr_value');
        const manualQr = document.getElementById('manualQr');
        const manualSubmit = document.getElementById('manualSubmit');
        const loginForm = document.getElementById('login-form');
        const toggleScanBtn = document.getElementById('toggleScanBtn');
        const scanButtonText = document.getElementById('scan-button-text');
        const videoPlaceholder = document.getElementById('video-placeholder');
        const scannerSection = document.getElementById('scanner-section');

        let scanning = false;
        let stream = null;

        toggleScanBtn.addEventListener('click', () => {
            hideMessage();
            if (scanning) {
                stopScan();
            } else {
                startScan();
            }
        });

        function startScan() {
            navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'environment'
                    }
                })
                .then(s => {
                    stream = s;
                    video.srcObject = stream;
                    video.setAttribute('playsinline', true);
                    videoPlaceholder.classList.add('hidden');
                    video.classList.remove('hidden');
                    video.play();
                    scanning = true;
                    scanButtonText.textContent = 'Stop Scan';
                    scannerSection.classList.remove('hidden'); // Show the scanner section
                    requestAnimationFrame(tick);
                })
                .catch(err => {
                    showMessage('Could not access camera. Please ensure camera permissions are granted.');
                    console.error('Camera access error:', err);
                });
        }

        function stopScan() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
            video.classList.add('hidden');
            videoPlaceholder.classList.remove('hidden');
            scanning = false;
            scanButtonText.textContent = 'Start QR Code Scan';
            scannerSection.classList.add('hidden'); // Hide the scanner section
        }

        function tick() {
            if (!scanning) return;

            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                canvasElement.height = video.videoHeight;
                canvasElement.width = video.videoWidth;
                canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
                const imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
                const code = jsQR(imageData.data, imageData.width, imageData.height);

                if (code) {
                    qrInput.value = code.data;
                    loginForm.submit();
                    stopScan();
                    return;
                }
            }
            requestAnimationFrame(tick);
        }

        manualSubmit.addEventListener('click', () => {
            hideMessage();
            stopScan(); // Stop scanning if it's active
            const manualValue = manualQr.value.trim();
            if (manualValue === '') {
                showMessage('Please enter a QR code value manually.');
                return;
            }
            qrInput.value = manualValue;
            loginForm.submit();
        });
    </script>
</body>

</html>
