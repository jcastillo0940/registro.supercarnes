@extends('layouts.admin') {{-- Usamos el layout con sidebar --}}
@section('content')
<div class="max-w-md mx-auto">
    <h2 class="text-2xl font-bold text-[#004691] mb-6 text-center italic">Panel de Evaluación</h2>
    
    <!-- Botones de Acción -->
    <div class="grid grid-cols-2 gap-4 mb-6">
        <a href="{{ route('jurado.scanner') }}" 
           class="bg-[#004691] hover:bg-[#003571] text-white p-4 rounded-2xl text-center font-black uppercase text-sm shadow-lg transition flex flex-col items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
            </svg>
            Escanear QR
        </a>
        
        <button onclick="document.getElementById('searchInput').focus()" 
                class="bg-[#FFD100] hover:bg-[#e6bd00] text-[#004691] p-4 rounded-2xl text-center font-black uppercase text-sm shadow-lg transition flex flex-col items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            Buscar Manual
        </button>
    </div>
    
    <div class="mb-6">
        <input type="text" id="searchInput" placeholder="Buscar fonda por nombre..." 
               class="w-full p-4 rounded-2xl border-2 border-gray-100 shadow-sm focus:border-yellow-400 outline-none">
    </div>
    
    <div class="space-y-4" id="fondaList">
        @foreach($fondas as $fonda)
            @php
                $yaVoto = $fonda->evaluaciones->where('user_id', auth()->id())->first();
            @endphp
            
            <div class="fonda-card bg-white p-5 rounded-3xl shadow-md border-l-8 {{ $yaVoto ? 'border-gray-300 opacity-60' : 'border-[#FFD100]' }}">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-black text-lg text-[#004691]">{{ $fonda->nombre_fonda }}</h3>
                        <p class="text-xs text-gray-500 uppercase font-bold">{{ $fonda->plato_preparar }}</p>
                    </div>
                    
                    @if($yaVoto)
                        <span class="bg-gray-100 text-gray-400 px-4 py-2 rounded-xl text-xs font-black uppercase">
                            ✅ Ya Calificado
                        </span>
                    @else
                        <a href="/evaluar/{{ $fonda->id }}" 
                           class="bg-[#004691] text-white px-6 py-2 rounded-xl text-xs font-black uppercase shadow-lg active:scale-90 transition">
                            Votar
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
<script>
    // Filtro simple de búsqueda
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        document.querySelectorAll('.fonda-card').forEach(card => {
            card.style.display = card.innerText.toLowerCase().includes(value) ? 'block' : 'none';
        });
    });
</script>
@endsection