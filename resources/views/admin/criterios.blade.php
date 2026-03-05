
@extends('layouts.admin')
@section('content')
<div class="bg-white p-6 rounded-2xl shadow-sm">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Gestión de Criterios</h2>
        <button onclick="document.getElementById('modalAdd').classList.toggle('hidden')" class="bg-green-600 text-white px-4 py-2 rounded-lg">+ Nueva Pregunta</button>
    </div>
    <table class="w-full text-left">
        <thead><tr class="border-b"> <th class="p-3">Nombre del Criterio</th> <th class="p-3">Estado</th> <th class="p-3">Acciones</th> </tr></thead>
        <tbody>
            @foreach($criterios as $c)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3">{{ $c->nombre }}</td>
                <td class="p-3"><span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">Activo</span></td>
                <td class="p-3">
                    <form action="/admin/criterios/delete/{{$c->id}}" method="POST" class="inline">@csrf @method('DELETE') <button class="text-red-500 font-bold">Eliminar</button></form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div id="modalAdd" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
    <div class="bg-white p-6 rounded-xl w-full max-w-sm">
        <h3 class="text-xl font-bold mb-4">Añadir Criterio</h3>
        <form action="/admin/criterios" method="POST">
            @csrf
            <input type="text" name="nombre" class="w-full border p-2 rounded mb-4" placeholder="Ej: Sabor del plato" required>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="this.closest('#modalAdd').classList.add('hidden')" class="bg-gray-200 px-4 py-2 rounded">Cancelar</button>
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection
