@extends('layouts.admin')

@section('page_title', 'Gestión de Jueces y Staff')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <div class="lg:col-span-1">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8">
            <h3 class="text-lg font-extrabold text-slate-800 mb-2">Nuevo Usuario</h3>
            <p class="text-xs text-slate-400 mb-6 uppercase tracking-widest font-semibold">Registro de credenciales</p>
            
            <form action="{{ route('admin.usuarios') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1 px-1">Nombre Completo</label>
                    <input type="text" name="name" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 outline-none text-sm transition-all">
                </div>
                
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1 px-1">Correo Electrónico</label>
                    <input type="email" name="email" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 outline-none text-sm transition-all">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1 px-1">Contraseña</label>
                    <input type="password" name="password" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 outline-none text-sm transition-all">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1 px-1">Rol de Usuario</label>
                    <select name="role" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-blue-500 outline-none text-sm font-semibold transition-all">
                        <option value="jurado">Jurado</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>

                <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-xl font-bold text-xs uppercase tracking-[0.2em] shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all mt-4">
                    Crear Usuario
                </button>
            </form>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-8 border-b border-slate-100">
                <h3 class="text-lg font-extrabold text-slate-800">Usuarios Registrados</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-[10px] uppercase tracking-widest text-slate-400 font-bold">
                            <th class="px-8 py-4">Usuario</th>
                            <th class="px-8 py-4">Rol</th>
                            <th class="px-8 py-4">Acceso</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($usuarios as $u)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-5">
                                <div class="font-bold text-slate-700 text-sm">{{ $u->name }}</div>
                                <div class="text-xs text-slate-400">{{ $u->email }}</div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter {{ $u->role == 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $u->role }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="text-[10px] text-slate-300 font-medium italic">
                                    Registrado el {{ $u->created_at->format('d/m/Y') }}
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection