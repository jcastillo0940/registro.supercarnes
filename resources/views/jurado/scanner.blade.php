@extends('layouts.admin')
@section('content')
<div id="scanner-app"></div>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script type="module">
    import React, { useEffect, useState } from 'https://esm.sh/react@18';
    import { createRoot } from 'https://esm.sh/react-dom@18/client';

    function App() {
        const [status, setStatus] = useState('Inicializando cámara...');

        useEffect(() => {
            let scanner;
            const start = async () => {
                scanner = new Html5Qrcode('reader');
                const cameras = await Html5Qrcode.getCameras();
                const cameraId = cameras?.[0]?.id;
                if (!cameraId) {
                    setStatus('No se encontró cámara. Usa búsqueda manual en panel.');
                    return;
                }

                await scanner.start(
                    cameraId,
                    { fps: 10, qrbox: { width: 250, height: 250 } },
                    (decodedText) => {
                        setStatus('QR detectado. Redirigiendo...');
                        window.location.href = decodedText;
                    },
                    () => {}
                );
                setStatus('Escaneando...');
            };

            start().catch(() => setStatus('No se pudo iniciar la cámara.'));
            return () => { if (scanner) scanner.stop().catch(() => {}); };
        }, []);

        return React.createElement('div', { className: 'max-w-2xl mx-auto' },
            React.createElement('h2', { className: 'text-2xl font-bold mb-4' }, 'Escáner QR (React)'),
            React.createElement('p', { className: 'mb-4 text-sm text-gray-600' }, status),
            React.createElement('div', { id: 'reader', className: 'bg-white rounded-xl p-2 shadow' })
        );
    }

    createRoot(document.getElementById('scanner-app')).render(React.createElement(App));
</script>
@endsection
