@extends('layouts.admin')

@section('page_title', 'Panel del Juez')

@section('content')
<div id="judge-app" class="animate-fade-in"></div>

<script>
    window.__JUDGE_PANEL__ = @json($initialState);
</script>
<script type="module">
    import React, { useMemo, useState } from 'https://esm.sh/react@18';
    import { createRoot } from 'https://esm.sh/react-dom@18/client';

    const data = window.__JUDGE_PANEL__ || { events: [], participants: [] };

    function App() {
        const [eventId, setEventId] = useState(data.events[0]?.id || '');
        const [term, setTerm] = useState('');

        const list = useMemo(() => data.participants.filter((p) => {
            const eventOk = eventId ? Number(p.event_id) === Number(eventId) : true;
            const searchOk = `${p.nombre_fonda} ${p.plato_preparar}`.toLowerCase().includes(term.toLowerCase());
            return eventOk && searchOk;
        }), [eventId, term]);

        return React.createElement('div', { className: 'max-w-4xl mx-auto space-y-10' },
            // Header
            React.createElement('div', { className: 'space-y-2' },
                React.createElement('div', { className: 'flex justify-between items-end' },
                    React.createElement('div', null,
                        React.createElement('h2', { className: 'text-[#002d5a] text-5xl font-black uppercase italic tracking-tighter' }, 'Panel de ', React.createElement('span', { className: 'text-blue-600' }, 'Evaluación')),
                        React.createElement('p', { className: 'text-slate-400 font-medium' }, 'Selecciona un participante para calificar su desempeño')
                    ),
                    React.createElement('a', { href: '{{ route('jurado.scanner') }}', className: 'bg-[#002d5a] text-white rounded-2xl px-8 py-4 font-black uppercase tracking-widest flex items-center shadow-xl shadow-blue-900/20 hover:scale-105 transition-all' }, 
                        React.createElement('svg', { xmlns: 'http://www.w3.org/2000/svg', className: 'h-6 w-6 mr-2', fill: 'none', viewBox: '0 0 24 24', stroke: 'currentColor' },
                            React.createElement('path', { strokeLinecap: 'round', strokeLinejoin: 'round', strokeWidth: 2, d: 'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 17h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z' })
                        ),
                        'Escanear QR'
                    )
                )
            ),

            // Filters
            React.createElement('div', { className: 'bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100' },
                React.createElement('div', { className: 'grid md:grid-cols-2 gap-6' },
                    React.createElement('div', { className: 'space-y-2' },
                        React.createElement('label', { className: 'text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4' }, 'Evento Actual'),
                        React.createElement('select', {
                            className: 'w-full bg-slate-50 border-2 border-transparent rounded-2xl p-4 font-bold text-slate-700 focus:bg-white focus:border-blue-500 transition-all outline-none',
                            value: eventId,
                            onChange: (e) => setEventId(e.target.value),
                        },
                            ...data.events.map((ev) => React.createElement('option', { key: ev.id, value: ev.id }, ev.nombre))
                        )
                    ),
                    React.createElement('div', { className: 'space-y-2' },
                        React.createElement('label', { className: 'text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4' }, 'Buscar por nombre o plato'),
                        React.createElement('input', {
                            className: 'w-full bg-slate-50 border-2 border-transparent rounded-2xl p-4 font-bold text-slate-700 focus:bg-white focus:border-blue-500 transition-all outline-none',
                            list: 'participants-list',
                            placeholder: 'Escribe el nombre aquí...',
                            value: term,
                            onChange: (e) => setTerm(e.target.value),
                        })
                    )
                )
            ),

            React.createElement('datalist', { id: 'participants-list' },
                ...data.participants.map((p) => React.createElement('option', { key: p.id, value: p.nombre_fonda }))
            ),

            // Participants List
            React.createElement('div', { className: 'grid grid-cols-1 md:grid-cols-2 gap-6' },
                ...list.map((p) => React.createElement('div', { key: p.id, className: 'group bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-blue-900/5 transition-all relative overflow-hidden' },
                    React.createElement('div', { className: 'relative z-10' },
                        React.createElement('div', { className: 'text-[10px] font-black uppercase tracking-widest text-blue-500 mb-2' }, p.nombre_persona),
                        React.createElement('h3', { className: 'text-2xl font-black text-[#002d5a] uppercase italic group-hover:text-blue-600 transition-colors' }, p.nombre_fonda),
                        React.createElement('p', { className: 'text-sm text-slate-400 font-medium mb-6' }, p.plato_preparar),
                        
                        p.evaluaciones?.length
                            ? React.createElement('div', { className: 'flex items-center text-green-600 font-black uppercase text-xs tracking-widest' }, 
                                React.createElement('svg', { xmlns: 'http://www.w3.org/2000/svg', className: 'h-5 w-5 mr-1', viewBox: '0 0 20 20', fill: 'currentColor' }, 
                                    React.createElement('path', { fillRule: 'evenodd', d: 'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z', clipRule: 'evenodd' })
                                ),
                                'Calificado'
                              )
                            : React.createElement('a', { 
                                href: `/evaluar/${p.uuid}`, 
                                className: 'inline-block bg-[#002d5a] text-white px-8 py-3 rounded-2xl font-black uppercase tracking-widest hover:bg-blue-900 transition-all hover:scale-105 shadow-lg shadow-blue-900/10' 
                              }, 'Evaluar Ahora')
                    ),
                    React.createElement('div', { className: 'absolute -right-4 -bottom-4 text-9xl text-slate-50 opacity-20 pointer-events-none group-hover:scale-110 transition-transform' }, '🍽️')
                ))
            )
        );
    }

    const root = document.getElementById('judge-app');
    createRoot(root).render(React.createElement(App));
</script>

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
