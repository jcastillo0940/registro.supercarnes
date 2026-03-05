
@extends('layouts.admin')
@section('content')
<div class="bg-white p-6 rounded-2xl shadow-sm">
    <h2 class="text-2xl font-bold mb-6 text-[#004691]">Lista de Fondas Inscritas</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="p-4 uppercase text-xs font-bold text-gray-500">Fonda</th>
                    <th class="p-4 uppercase text-xs font-bold text-gray-500">Responsable</th>
                    <th class="p-4 uppercase text-xs font-bold text-gray-500">Cédula / Tel</th>
                    <th class="p-4 uppercase text-xs font-bold text-gray-500 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($participantes as $p)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4">
                        <div class="font-bold">{{ $p->nombre_fonda }}</div>
                        <div class="text-xs text-gray-400">{{ $p->plato_preparar }}</div>
                    </td>
                    <td class="p-4 text-sm">{{ $p->nombre_persona }}</td>
                    <td class="p-4 text-sm">{{ $p->cedula }}<br>{{ $p->telefono }}</td>
                    <td class="p-4 text-right">
                        <form action="/admin/participantes/{{$p->id}}" method="POST" onsubmit="return confirm('¿Eliminar esta fonda?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:underline font-bold">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
