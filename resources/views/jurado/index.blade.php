@extends('layouts.admin')
@section('content')
<div id="judge-app"></div>
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

        return React.createElement('div', { className: 'max-w-3xl mx-auto space-y-4' },
            React.createElement('h2', { className: 'text-2xl font-bold text-[#004691]' }, 'Panel del Juez (React)'),
            React.createElement('div', { className: 'grid md:grid-cols-3 gap-3' },
                React.createElement('select', {
                    className: 'border rounded p-2',
                    value: eventId,
                    onChange: (e) => setEventId(e.target.value),
                },
                    ...data.events.map((ev) => React.createElement('option', { key: ev.id, value: ev.id }, ev.nombre))
                ),
                React.createElement('input', {
                    className: 'border rounded p-2',
                    list: 'participants-list',
                    placeholder: 'Búsqueda manual/autocompletar',
                    value: term,
                    onChange: (e) => setTerm(e.target.value),
                }),
                React.createElement('a', { href: '{{ route('jurado.scanner') }}', className: 'bg-[#004691] text-white rounded p-2 text-center' }, 'Escanear QR')
            ),
            React.createElement('datalist', { id: 'participants-list' },
                ...data.participants.map((p) => React.createElement('option', { key: p.id, value: p.nombre_fonda }))
            ),
            React.createElement('div', { className: 'space-y-3' },
                ...list.map((p) => React.createElement('div', { key: p.id, className: 'bg-white p-4 rounded-xl shadow border' },
                    React.createElement('div', { className: 'flex justify-between' },
                        React.createElement('div', null,
                            React.createElement('div', { className: 'font-bold' }, p.nombre_fonda),
                            React.createElement('div', { className: 'text-xs text-gray-500' }, p.plato_preparar)
                        ),
                        p.evaluaciones?.length
                            ? React.createElement('span', { className: 'text-xs bg-gray-100 px-2 py-1 rounded' }, 'Ya calificado')
                            : React.createElement('a', { href: `/evaluar/${p.uuid}`, className: 'bg-blue-600 text-white px-3 py-1 rounded' }, 'Evaluar')
                    )
                ))
            )
        );
    }

    const root = document.getElementById('judge-app');
    createRoot(root).render(React.createElement(App));
</script>
@endsection
