<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Resultados en vivo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100">
<div id="results-app"></div>

<script>
    window.__RESULTS__ = @json($initialState);
    window.__RESULTS_ADMIN__ = @json($isAdmin);
</script>
<script type="module">
    import React, { useEffect, useMemo, useState } from 'https://esm.sh/react@18';
    import { createRoot } from 'https://esm.sh/react-dom@18/client';
    import Chart from 'https://esm.sh/chart.js/auto';

    const state = window.__RESULTS__ || { ranking: [], events: [], criterios: [] };
    const isAdmin = !!window.__RESULTS_ADMIN__;

    function App() {
        const [eventId, setEventId] = useState(state.selectedEventId || '');
        const [criterioId, setCriterioId] = useState(state.selectedCriterioId || '');

        const chartData = useMemo(() => state.ranking.slice(0, 10), []);

        useEffect(() => {
            const ctx = document.getElementById('rankingChart');
            if (!ctx) return;
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.map((x) => x.nombre_fonda),
                    datasets: [{
                        label: 'Puntaje Final',
                        data: chartData.map((x) => x.final_score),
                        backgroundColor: '#1d4ed8',
                    }],
                },
                options: { responsive: true, plugins: { legend: { display: false } } },
            });
            return () => chart.destroy();
        }, [chartData]);

        const buildUrl = (base) => {
            const params = new URLSearchParams();
            if (eventId) params.set('event_id', eventId);
            if (criterioId) params.set('criterio_id', criterioId);
            const q = params.toString();
            return q ? `${base}?${q}` : base;
        };

        return React.createElement('div', { className: 'max-w-6xl mx-auto py-8 px-4 space-y-6' },
            React.createElement('div', { className: 'bg-white rounded-xl p-4 shadow' },
                React.createElement('h1', { className: 'text-2xl font-bold mb-3' }, isAdmin ? 'Dashboard Pro de Resultados' : 'Resultados en vivo'),
                React.createElement('div', { className: 'grid md:grid-cols-3 gap-3' },
                    React.createElement('select', { className: 'border rounded p-2', value: eventId, onChange: (e) => setEventId(e.target.value) },
                        ...state.events.map((ev) => React.createElement('option', { key: ev.id, value: ev.id }, ev.nombre))
                    ),
                    React.createElement('select', { className: 'border rounded p-2', value: criterioId, onChange: (e) => setCriterioId(e.target.value) },
                        React.createElement('option', { value: '' }, 'Todos los criterios'),
                        ...state.criterios.map((c) => React.createElement('option', { key: c.id, value: c.id }, c.nombre))
                    ),
                    React.createElement('button', { className: 'bg-blue-600 text-white rounded p-2', onClick: () => window.location.href = buildUrl(window.location.pathname) }, 'Aplicar filtros')
                )
            ),

            React.createElement('div', { className: 'bg-white rounded-xl p-4 shadow' },
                React.createElement('canvas', { id: 'rankingChart', height: 120 })
            ),

            isAdmin ? React.createElement('div', { className: 'flex gap-3' },
                React.createElement('a', { href: buildUrl('/admin/resultados/exportar-csv'), className: 'bg-emerald-600 text-white px-4 py-2 rounded' }, 'Exportar CSV'),
                React.createElement('a', { href: buildUrl('/admin/resultados/exportar-pdf'), className: 'bg-red-600 text-white px-4 py-2 rounded' }, 'Exportar PDF')
            ) : null,

            React.createElement('div', { className: 'bg-white rounded-xl p-4 shadow overflow-auto' },
                React.createElement('table', { className: 'w-full text-sm' },
                    React.createElement('thead', null,
                        React.createElement('tr', { className: 'text-left border-b' },
                            ...['#', 'Participante', 'Prom. Jueces', 'Ajuste', 'Final', 'Votos públicos'].map((h) => React.createElement('th', { key: h, className: 'py-2' }, h))
                        )
                    ),
                    React.createElement('tbody', null,
                        ...state.ranking.map((row) => React.createElement('tr', { key: row.id, className: 'border-b' },
                            React.createElement('td', { className: 'py-2' }, row.posicion),
                            React.createElement('td', { className: 'py-2 font-semibold' }, row.nombre_fonda),
                            React.createElement('td', { className: 'py-2' }, row.judge_avg),
                            React.createElement('td', { className: 'py-2' }, row.ajuste_admin),
                            React.createElement('td', { className: 'py-2 font-bold' }, row.final_score),
                            React.createElement('td', { className: 'py-2' }, row.public_votes_count),
                        ))
                    )
                )
            )
        );
    }

    createRoot(document.getElementById('results-app')).render(React.createElement(App));
</script>
</body>
</html>
