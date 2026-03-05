@extends('layouts.admin')

@section('page_title', 'Ruta de Logística')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-white">
        <div>
            <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Ruta de Visitas</h3>
            <p class="text-sm text-slate-400">Organiza el recorrido oficial de los jueces</p>
        </div>
        
        <a href="{{ route('admin.pdf') }}" target="_blank" class="flex items-center px-5 py-2.5 bg-slate-800 text-white rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-slate-700 transition-all shadow-md active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Imprimir Lista PDF
        </a>
    </div>

    <form action="{{ route('admin.guardarOrden') }}" method="POST" class="p-8">
        @csrf
        <div class="overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-y-2">
                <thead>
                    <tr class="text-[10px] uppercase tracking-[0.2em] text-slate-400 font-black">
                        <th class="px-4 py-2">Orden</th>
                        <th class="px-4 py-2">Fonda / Responsable</th>
                        <th class="px-4 py-2">Ubicación Exacta</th>
                        <th class="px-4 py-2 text-center">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($participantes->sortBy('orden_visita') as $p)
                    <tr class="bg-slate-50 hover:bg-slate-100 transition-colors group">
                        <td class="px-4 py-4 first:rounded-l-2xl">
                            <input type="number" name="orden[{{$p->id}}]" value="{{$p->orden_visita}}" 
                                   class="w-16 bg-white border-2 border-slate-200 rounded-xl p-2 text-center font-black text-blue-600 focus:border-blue-500 outline-none transition-all">
                        </td>
                        <td class="px-4 py-4">
                            <div class="font-bold text-slate-700 uppercase text-sm">{{ $p->nombre_fonda }}</div>
                            <div class="text-[10px] text-slate-400 font-medium">{{ $p->nombre_persona }}</div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="text-xs text-slate-600 font-medium italic italic leading-tight">{{ $p->ubicacion }}</div>
                        </td>
                        <td class="px-4 py-4 last:rounded-r-2xl text-center">
                            <span class="text-[9px] bg-amber-100 text-amber-700 px-3 py-1.5 rounded-full font-black uppercase tracking-widest">
                                Pendiente
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-10 flex items-center justify-between bg-slate-50 p-6 rounded-2xl border border-dashed border-slate-200">
            <p class="text-xs text-slate-500 max-w-xs">
                Asegúrate de guardar los cambios antes de imprimir el PDF para que el orden se vea reflejado en el reporte.
            </p>
            <button type="submit" class="flex items-center bg-[#004691] text-white px-8 py-4 rounded-xl font-bold text-xs uppercase tracking-[0.2em] shadow-xl shadow-blue-200 hover:bg-blue-800 transition-all active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 002-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                Guardar Orden de Ruta
            </button>
        </div>
    </form>
</div>
@endsection