<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Resultados Oficiales | Super Carnes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,900&family=Inter:wght@300;400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
        .glass { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
        .gold-gradient { background: linear-gradient(135deg, #BF953F, #FCF6BA, #B38728, #FBF5B7, #AA771C); }
        .navy-gradient { background: linear-gradient(135deg, #002d5a 0%, #004691 100%); }
        @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .animate-rank { animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
</head>
<body class="bg-[#f8fafc] text-[#002d5a]">
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

        const chartData = useMemo(() => state.ranking.slice(0, 5), [state.ranking]);

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
                        backgroundColor: '#004691',
                        borderRadius: 12,
                        borderSkipped: false,
                    }],
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { display: false }, ticks: { font: { weight: 'bold' } } },
                        x: { grid: { display: false }, ticks: { font: { weight: 'bold' } } }
                    }
                },
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

        return React.createElement('div', { className: 'max-w-7xl mx-auto py-12 px-6 space-y-10' },
            // Hero Section
            React.createElement('div', { className: 'text-center space-y-4' },
                React.createElement('div', { className: 'inline-block px-4 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-black uppercase tracking-widest' }, 'Live Leaderboard'),
                React.createElement('h1', { className: 'text-6xl lg:text-8xl font-black uppercase italic tracking-tighter navy-gradient bg-clip-text text-transparent leading-none' }, 'Resultados'),
                React.createElement('p', { className: 'text-slate-400 font-medium text-lg' }, 'Sigue en tiempo real la tabla de posiciones oficial')
            ),

            // Filters
            React.createElement('div', { className: 'glass rounded-[2.5rem] p-8 shadow-2xl shadow-blue-900/10 border border-white' },
                React.createElement('div', { className: 'grid md:grid-cols-4 gap-4 items-end' },
                    React.createElement('div', { className: 'space-y-2' },
                        React.createElement('label', { className: 'text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4' }, 'Evento'),
                        React.createElement('select', { className: 'w-full bg-slate-50 border-2 border-transparent rounded-2xl p-4 font-bold text-slate-700 focus:bg-white focus:border-blue-500 transition-all outline-none', value: eventId, onChange: (e) => setEventId(e.target.value) },
                            ...state.events.map((ev) => React.createElement('option', { key: ev.id, value: ev.id }, ev.nombre))
                        )
                    ),
                    React.createElement('div', { className: 'space-y-2' },
                        React.createElement('label', { className: 'text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4' }, 'Categoría / Criterio'),
                        React.createElement('select', { className: 'w-full bg-slate-50 border-2 border-transparent rounded-2xl p-4 font-bold text-slate-700 focus:bg-white focus:border-blue-500 transition-all outline-none', value: criterioId, onChange: (e) => setCriterioId(e.target.value) },
                            React.createElement('option', { value: '' }, 'Global (Todos)'),
                            ...state.criterios.map((c) => React.createElement('option', { key: c.id, value: c.id }, c.nombre))
                        )
                    ),
                    React.createElement('button', { 
                        className: 'md:col-span-2 bg-[#002d5a] text-white rounded-2xl p-4 font-black uppercase tracking-widest hover:bg-blue-900 transition-all transform hover:scale-[1.02] shadow-xl shadow-blue-900/20',
                        onClick: () => window.location.href = buildUrl(window.location.pathname) 
                    }, 'Actualizar Ranking')
                )
            ),

            // Top 1 Visual Highlights
            state.ranking[0] ? React.createElement('div', { className: 'grid lg:grid-cols-3 gap-8' },
                React.createElement('div', { className: 'lg:col-span-2 bg-white rounded-[3rem] p-10 shadow-xl border border-slate-50 relative overflow-hidden' },
                    React.createElement('div', { className: 'h-[350px] relative' },
                        React.createElement('canvas', { id: 'rankingChart' })
                    )
                ),
                React.createElement('div', { className: 'gold-gradient rounded-[3rem] p-1 shadow-2xl' },
                    React.createElement('div', { className: 'bg-[#002d5a] h-full rounded-[2.8rem] p-10 flex flex-col items-center justify-center text-center space-y-6 relative overflow-hidden' },
                        React.createElement('div', { className: 'absolute inset-0 opacity-10 pointer-events-none' }, '⭐'.repeat(100)),
                        React.createElement('div', { className: 'text-7xl' }, '👑'),
                        React.createElement('div', null,
                            React.createElement('div', { className: 'text-blue-300 text-[10px] font-black uppercase tracking-[0.3em] mb-2' }, 'Líder Actual'),
                            React.createElement('h2', { className: 'text-4xl font-black text-white uppercase italic leading-tight' }, state.ranking[0].nombre_fonda),
                        ),
                        React.createElement('div', { className: 'text-6xl font-black text-yellow-400 font-serif italic' }, state.ranking[0].final_score)
                    )
                )
            ) : null,

            // Full List
            React.createElement('div', { className: 'bg-white rounded-[3rem] shadow-2xl shadow-slate-200/50 overflow-hidden border border-slate-100' },
                React.createElement('div', { className: 'overflow-x-auto px-8' },
                    React.createElement('table', { className: 'w-full border-collapse' },
                        React.createElement('thead', null,
                            React.createElement('tr', null,
                                ...['Rank', 'Participante', 'Score Jueces', 'Public Votes', 'Final Score'].map((h, i) => 
                                    React.createElement('th', { key: h, className: `p-8 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-100 ${i === 0 ? 'text-left' : i === 1 ? 'text-left' : 'text-center'}` }, h)
                                )
                            )
                        ),
                        React.createElement('tbody', { className: 'divide-y divide-slate-50' },
                            ...state.ranking.map((row, idx) => React.createElement('tr', { key: row.id, className: 'group transition-all hover:bg-slate-50/50' },
                                React.createElement('td', { className: 'p-8' }, 
                                    idx === 0 ? '👑' : idx === 1 ? '🥈' : idx === 2 ? '🥉' : React.createElement('span', { className: 'text-xl font-black text-slate-200' }, `#${idx + 1}`)
                                ),
                                React.createElement('td', { className: 'p-8' }, 
                                    React.createElement('div', { className: 'text-xl font-bold text-[#002d5a] uppercase italic' }, row.nombre_fonda),
                                    React.createElement('div', { className: 'text-xs text-slate-400 font-medium' }, row.plato_preparar)
                                ),
                                React.createElement('td', { className: 'p-8 text-center' }, React.createElement('span', { className: 'bg-slate-100 px-4 py-2 rounded-xl font-black text-slate-600' }, row.judge_avg)),
                                React.createElement('td', { className: 'p-8 text-center' }, React.createElement('span', { className: 'text-slate-400 font-bold' }, row.public_votes_count)),
                                React.createElement('td', { className: 'p-8 text-center' }, 
                                    React.createElement('span', { className: `text-3xl font-black tracking-tighter ${idx === 0 ? 'text-yellow-600 font-serif' : 'text-[#002d5a]'}` }, row.final_score)
                                ),
                            ))
                        )
                    )
                )
            )
        );
    }

    createRoot(document.getElementById('results-app')).render(React.createElement(App));
</script>
</body>
</html>

