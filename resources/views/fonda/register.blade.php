<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripciones Cerradas - Fonda Challenge 2026</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-supercarnes { background-color: #004691; }
        .text-supercarnes { color: #004691; }
        .bg-accent { background-color: #FFD100; }
        
        html, body { height: 100%; }
        .form-container { min-height: 100vh; display: flex; flex-direction: column; }
        .form-content { flex: 1; overflow-y: auto; overflow-x: hidden; }

        @keyframes scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .animate-scroll { animation: scroll 20s linear infinite; }
    </style>
</head>
<body class="bg-supercarnes">

    <div class="form-container">
        <div class="bg-white min-h-screen lg:h-screen flex flex-col lg:flex-row">
            
            <div class="lg:w-2/5 bg-supercarnes flex flex-col justify-center items-center py-10 px-6 lg:p-12">
                <div class="text-center">
                    <div class="mb-6 lg:mb-10">
                        <img src="https://apps.supercarnes.com/wp-content/uploads/elementor/thumbs/Recurso-1-qmfaz40h3cog36mfzpzjw2skienqu9j8xprd9b7uha.png" 
                             alt="Super Carnes" 
                             class="w-40 lg:w-72 mx-auto">
                    </div>
                    
                    <h1 class="text-3xl lg:text-6xl font-black text-white uppercase italic tracking-tighter leading-tight mb-4">
                        Fonda Challenge
                    </h1>
                    <div class="w-20 h-2 bg-accent mx-auto mb-6"></div>
                </div>
            </div>

            <div class="lg:w-3/5 flex flex-col justify-center form-content bg-slate-50">
                <div class="p-8 lg:p-16 text-center">
                    
                    <div class="mb-8 flex justify-center">
                        <div class="bg-amber-100 p-6 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    
                    <h2 class="text-4xl lg:text-6xl font-black text-supercarnes uppercase italic tracking-tight mb-6">
                        ¡Gracias por tu interés!
                    </h2>
                    
                    <div class="bg-white border-2 border-dashed border-slate-200 rounded-3xl p-8 shadow-sm">
                        <p class="text-xl lg:text-2xl font-bold text-slate-700 mb-4 italic">
                            Periodo de Inscripción Finalizado
                        </p>
                        <p class="text-slate-500 text-base lg:text-lg leading-relaxed">
                            La fecha límite para el registro de fondas en el <strong>Fonda Challenge 2026</strong> ha concluido. 
                            ¡Estén atentos a nuestras redes sociales para conocer a los participantes seleccionados!
                        </p>
                    </div>

                    <div class="mt-10">
                        <p class="text-supercarnes font-bold uppercase tracking-widest text-sm mb-6">
                            Sigue la competencia en Super Carnes
                        </p>
                        <a href="https://supercarnes.com" class="inline-block bg-supercarnes text-white font-black px-10 py-4 rounded-2xl hover:scale-105 transition-transform shadow-lg">
                            Visitar Sitio Web
                        </a>
                    </div>

                    <div class="mt-12 overflow-hidden opacity-40">
                        <div class="carousel-track flex gap-4 animate-scroll">
                            <div class="flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden grayscale"><img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=200" class="w-full h-full object-cover"></div>
                            <div class="flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden grayscale"><img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=200" class="w-full h-full object-cover"></div>
                            <div class="flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden grayscale"><img src="https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=200" class="w-full h-full object-cover"></div>
                            <div class="flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden grayscale"><img src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=200" class="w-full h-full object-cover"></div>
                            <div class="flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden grayscale"><img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=200" class="w-full h-full object-cover"></div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 text-center border-t border-slate-100 mt-auto">
                    <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold">
                        © 2026 Super Carnes - Gestión de Eventos
                    </p>
                </div>
            </div>

        </div>
    </div>

</body>
</html>