@extends('layouts.admin')

@section('page_title', 'Control de Accesos')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in space-y-10">
    <div class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-5xl font-black text-[#002d5a] uppercase italic tracking-tighter">Gestión de <span class="text-blue-600">Personal</span></h1>
            <p class="text-slate-500 font-medium">Control de acceso para administradores y jurados oficiales</p>
        </div>
        <div class="bg-blue-50 text-blue-700 px-6 py-2 rounded-2xl font-black uppercase text-xs tracking-widest border border-blue-100 italic">
            Total Usuarios: {{ count($usuarios) }}
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- New User Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 p-10 border border-slate-100 sticky top-10">
                <div class="mb-8">
                    <h3 class="text-2xl font-black text-[#002d5a] uppercase italic tracking-tighter">Nuevo <span class="text-blue-600">Registro</span></h3>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-1">Credenciales de acceso</p>
                </div>
                
                <form action="{{ route('admin.usuarios') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Nombre Completo</label>
                        <input type="text" name="name" required class="w-full bg-slate-50 border-2 border-transparent rounded-2xl p-4 font-bold text-slate-700 focus:bg-white focus:border-blue-500 transition-all outline-none shadow-sm">
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Correo Electrónico</label>
                        <input type="email" name="email" required class="w-full bg-slate-50 border-2 border-transparent rounded-2xl p-4 font-bold text-slate-700 focus:bg-white focus:border-blue-500 transition-all outline-none shadow-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Contraseña Maestra</label>
                        <input type="password" name="password" required class="w-full bg-slate-50 border-2 border-transparent rounded-2xl p-4 font-bold text-slate-700 focus:bg-white focus:border-blue-500 transition-all outline-none shadow-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Rol de Sistema</label>
                        <select name="role" class="w-full bg-slate-50 border-2 border-transparent rounded-2xl p-4 font-bold text-slate-700 focus:bg-white focus:border-blue-500 transition-all outline-none shadow-sm">
                            <option value="jurado">Jurado / Juez</option>
                            <option value="admin">Administrador Pro</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-[#002d5a] hover:bg-blue-900 text-white py-5 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl shadow-blue-900/20 transition-all hover:scale-105 active:scale-95 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Dar de Alta Usuario
                    </button>
                </form>
            </div>
        </div>

        <!-- Registered Users Table -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 overflow-hidden border border-slate-100">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-slate-50">
                                <th class="p-8 text-left text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-100">Información de Usuario</th>
                                <th class="p-8 text-left text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-100">Privilegios</th>
                                <th class="p-8 text-right text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-100">Fecha de Alta</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($usuarios as $u)
                            <tr class="group hover:bg-slate-50/50 transition-all duration-300">
                                <td class="p-8">
                                    <div class="text-lg font-bold text-[#002d5a] group-hover:text-blue-600 transition-colors uppercase italic">{{ $u->name }}</div>
                                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $u->email }}</div>
                                </td>
                                <td class="p-8">
                                    <span class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border {{ $u->role == 'admin' ? 'bg-purple-50 text-purple-600 border-purple-100' : 'bg-blue-50 text-blue-600 border-blue-100' }}">
                                        {{ $u->role }}
                                    </span>
                                </td>
                                <td class="p-8 text-right">
                                    <div class="text-sm font-black text-slate-300 italic group-hover:text-slate-500 transition-colors">
                                        {{ $u->created_at->format('d M Y') }}
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