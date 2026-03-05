@extends('layouts.admin')

@section('page_title', 'Ruta Logística Oficial')

@section('content')
<div class="max-w-6xl mx-auto animate-fade-in space-y-10">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-5xl font-black text-[#002d5a] uppercase italic tracking-tighter">Hoja de <span class="text-blue-600">Ruta</span></h1>
            <p class="text-slate-500 font-medium">Planificación logística para el recorrido del jurado</p>
        </div>
        <a href="{{ route('admin.pdf') }}" target="_blank" class="bg-slate-800 hover:bg-slate-900 text-white px-8 py-3 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl shadow-slate-800/20 transition-all hover:scale-105 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Imprimir Ruta PDF
        </a>
    </div>

    <form action="{{ route('admin.guardarOrden') }}" method="POST">
        @csrf
        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 overflow-hidden border border-slate-100">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="p-8 text-left text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-100">Orden</th>
                            <th class="p-8 text-left text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-100">Participante / Responsable</th>
                            <th class="p-8 text-left text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-100">Localización / Punto de Visita</th>
                            <th class="p-8 text-center text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-100">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($participantes->sortBy('orden_visita') as $p)
                        <tr class="group hover:bg-slate-50/50 transition-all duration-300">
                            <td class="p-8">
                                <input type="number" name="orden[{{$p->id}}]" value="{{$p->orden_visita}}" 
                                       class="w-20 bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 text-center font-black text-blue-600 focus:bg-white focus:border-blue-500 outline-none transition-all shadow-inner">
                            </td>
                            <td class="p-8">
                                <div class="text-lg font-bold text-[#002d5a] group-hover:text-blue-600 transition-colors uppercase italic">{{ $p->nombre_fonda }}</div>
                                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $p->nombre_persona }}</div>
                            </td>
                            <td class="p-8">
                                <div class="text-sm text-slate-500 font-medium italic leading-relaxed group-hover:text-slate-700 transition-colors">
                                    {{ $p->ubicacion }}
                                </div>
                            </td>
                            <td class="p-8 text-center">
                                <span class="bg-amber-50 text-amber-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border border-amber-100">
                                    En Espera
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-10 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                <div class="flex items-center text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-[10px] font-bold uppercase tracking-widest max-w-[280px]">Guarda el orden de la ruta antes de generar el PDF oficial para los jueces.</p>
                </div>
                <button type="submit" class="bg-[#002d5a] hover:bg-blue-900 text-white px-10 py-4 rounded-2xl font-black uppercase text-xs tracking-[0.2em] shadow-xl shadow-blue-900/20 transition-all hover:scale-105 active:scale-95 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 002-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Actualizar Ruta
                </button>
            </div>
        </div>
    </form>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>
@endsection