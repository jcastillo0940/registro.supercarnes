@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto">
    <h2 class="text-3xl font-black text-[#004691] mb-2 text-center italic">Escanear Código QR</h2>
    <p class="text-center text-gray-500 mb-8 font-semibold">Apunta la cámara al código QR de la fonda</p>
    
    <!-- Contenedor de la Cámara -->
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border-8 border-[#004691] mb-6">
        <div id="reader" class="w-full"></div>
    </div>

    <!-- Estado del Escáner -->
    <div id="scanStatus" class="hidden mb-6">
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl" role="alert">
            <div class="flex items-center">
                <svg class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="font-bold">¡QR Detectado!</p>
                    <p class="text-sm" id="scannedText">Redirigiendo...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Información -->
    <div class="bg-blue-50 rounded-2xl p-6 border border-blue-200">
        <div class="flex items-start">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="text-sm text-blue-800">
                <p class="font-bold mb-2">📱 Instrucciones:</p>
                <ol class="space-y-1 list-decimal list-inside">
                    <li>Permite el acceso a la cámara cuando se solicite</li>
                    <li>Centra el código QR en la pantalla</li>
                    <li>Espera a que se detecte automáticamente</li>
                    <li>Serás redirigido al formulario de evaluación</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Botón Manual (Alternativa) -->
    <div class="mt-6 text-center">
        <p class="text-gray-500 text-sm mb-3">¿No puedes escanear?</p>
        <a href="{{ route('jurado.panel') }}" 
           class="inline-block bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-bold transition uppercase text-sm">
            Buscar Manualmente
        </a>
    </div>
</div>

<!-- Librería HTML5-QRCode -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
    let html5QrcodeScanner;
    let isProcessing = false;

    function onScanSuccess(decodedText, decodedResult) {
        // Si ya estamos procesando, ignorar
        if (isProcessing) {
            return;
        }
        
        // Marcar como procesando
        isProcessing = true;
        
        console.log('Código QR detectado:', decodedText);
        console.log('Redirigiendo ahora...');
        
        // Detener el escáner
        try {
            html5QrcodeScanner.clear();
        } catch(e) {
            console.log('Error deteniendo escáner:', e);
        }
        
        // Mostrar mensaje
        const statusDiv = document.getElementById('scanStatus');
        const textDiv = document.getElementById('scannedText');
        statusDiv.classList.remove('hidden');
        textDiv.textContent = 'Redirigiendo...';
        
        // REDIRECCIÓN DIRECTA Y FORZADA
        console.log('Ejecutando window.location.href...');
        window.location.href = decodedText;
        
        // Backup: Si no funciona en 1 segundo, forzar con replace
        setTimeout(() => {
            console.log('Forzando redirección con replace...');
            window.location.replace(decodedText);
        }, 1000);
        
        // Último recurso: Usar assign
        setTimeout(() => {
            console.log('Último intento con assign...');
            window.location.assign(decodedText);
        }, 2000);
    }

    function onScanError(errorMessage) {
        // Silenciar errores normales de escaneo
    }

    // Configuración del escáner
    const config = {
        fps: 10,
        qrbox: { width: 250, height: 250 },
        aspectRatio: 1.0,
        rememberLastUsedCamera: true,
        showTorchButtonIfSupported: true
    };

    // Inicializar escáner
    html5QrcodeScanner = new Html5Qrcode("reader");

    // Obtener cámaras disponibles y usar la trasera si está disponible
    Html5Qrcode.getCameras().then(cameras => {
        if (cameras && cameras.length) {
            // Buscar cámara trasera (environment)
            let cameraId = cameras[0].id;
            
            cameras.forEach(camera => {
                if (camera.label.toLowerCase().includes('back') || 
                    camera.label.toLowerCase().includes('rear') ||
                    camera.label.toLowerCase().includes('environment')) {
                    cameraId = camera.id;
                }
            });
            
            // Iniciar escáner con la cámara seleccionada
            html5QrcodeScanner.start(
                cameraId,
                config,
                onScanSuccess,
                onScanError
            ).catch(err => {
                console.error('Error al iniciar el escáner:', err);
                alert('No se pudo acceder a la cámara. Verifica los permisos.');
            });
        }
    }).catch(err => {
        console.error('Error al obtener cámaras:', err);
        alert('No se detectaron cámaras. Usa la búsqueda manual.');
    });

    // Limpiar al salir de la página
    window.addEventListener('beforeunload', () => {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear();
        }
    });
</script>

<style>
    /* Estilos personalizados para el escáner */
    #reader {
        border: none;
    }
    
    #reader video {
        border-radius: 1rem;
    }
    
    /* Ocultar elementos innecesarios de la librería */
    #reader__dashboard_section_csr {
        display: none !important;
    }
    
    #reader__dashboard_section_swaplink {
        margin-top: 1rem;
    }
    
    #reader__dashboard_section_swaplink button {
        background: #004691 !important;
        color: white !important;
        border-radius: 0.75rem !important;
        padding: 0.75rem 1.5rem !important;
        font-weight: bold !important;
        text-transform: uppercase !important;
        font-size: 0.875rem !important;
    }
</style>
@endsection
