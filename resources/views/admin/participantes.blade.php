@extends('layouts.admin')

@section('page_title', 'Gestión de Participantes')

@section('content')
<div class="max-w-6xl mx-auto animate-fade-in">
    <div class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-5xl font-black text-[#002d5a] uppercase italic tracking-tighter">Lista de <span class="text-blue-600">Participantes</span></h1>
            <p class="text-slate-500 font-medium">Administración de registros oficiales del evento</p>
        </div>
        <div class="bg-blue-50 text-blue-700 px-6 py-2 rounded-2xl font-black uppercase text-xs tracking-widest border border-blue-100 italic">
            Total: {{ count($participantes) }} Registrados
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 overflow-hidden border border-slate-100">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="p-8 text-left text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-100">Participante / Equipo</th>
                        <th class="p-8 text-left text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-100">Responsable</th>
                        <th class="p-8 text-left text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-100">Contacto</th>
                        <th class="p-8 text-right text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-100">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($participantes as $p)
                    <tr class="group hover:bg-slate-50/50 transition-all duration-300">
                        <td class="p-8">
                            <div class="text-lg font-bold text-[#002d5a] group-hover:text-blue-600 transition-colors uppercase italic">{{ $p->nombre_fonda }}</div>
                            <div class="text-xs font-medium text-slate-400 uppercase tracking-widest">{{ $p->plato_preparar }}</div>
                        </td>
                        <td class="p-8">
                            <div class="font-bold text-slate-700 uppercase italic">{{ $p->nombre_persona }}</div>
                        </td>
                        <td class="p-8 text-sm space-y-1">
                            <div class="flex items-center text-slate-500">
                                <span class="font-black text-[10px] uppercase tracking-widest mr-2 text-slate-300">ID:</span> {{ $p->cedula }}
                            </div>
                            <div class="flex items-center text-slate-500">
                                <span class="font-black text-[10px] uppercase tracking-widest mr-2 text-slate-300">TEL:</span> {{ $p->telefono }}
                            </div>
                        </td>
                        <td class="p-8 text-right">
                            <form action="/admin/participantes/{{$p->id}}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este registro?')">
                                @csrf @method('DELETE')
                                <button class="bg-white border-2 border-red-100 text-red-500 px-4 py-2 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-red-500 hover:text-white hover:border-red-500 transition-all shadow-sm">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
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
