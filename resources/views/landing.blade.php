<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Eventos Oficiales | Super Carnes</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,900;1,900&family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        window.__EVENT_APP__ = @json($initialState);
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
        @keyframes fade-in { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fade-in 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen relative overflow-x-hidden">
    <!-- Animated Background -->
    <div class="fixed inset-0 pointer-events-none opacity-20">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-600 rounded-full blur-[150px]"></div>
        <div class="absolute top-1/2 -right-40 w-80 h-80 bg-blue-900 rounded-full blur-[120px]"></div>
        <div class="absolute -bottom-40 left-1/3 w-96 h-96 bg-indigo-600 rounded-full blur-[150px]"></div>
    </div>

    <div id="app" class="relative z-10"></div>

    <script type="module">
        import React, { createContext, useContext, useMemo, useState } from 'https://esm.sh/react@18';
        import { createRoot } from 'https://esm.sh/react-dom@18/client';

        const EventContext = createContext({ currentEvent: null, setCurrentEvent: () => {} });

        function EventLayout({ children }) {
            const { currentEvent } = useContext(EventContext);
            
            return React.createElement('div', { className: 'min-h-screen flex flex-col' },
                React.createElement('header', { className: 'p-10 text-center animate-fade-in' },
                    React.createElement('div', { className: 'mx-auto max-w-4xl' },
                        React.createElement('span', { className: 'text-[10px] font-black uppercase tracking-[0.5em] text-blue-400 mb-2 block' }, 'Plataforma Oficial de Certificación'),
                        React.createElement('h1', { className: 'font-serif text-5xl italic font-black text-white' }, 'Super Carnes ', React.createElement('span', { className: 'text-blue-500' }, 'Events')),
                        React.createElement('p', { className: 'text-slate-500 text-xs font-bold uppercase tracking-widest mt-4' }, 'Calendario de Experiencias y Votaciones 2026')
                    )
                ),
                React.createElement('main', { className: 'flex-grow mx-auto w-full max-w-6xl p-6 sm:p-10' }, children),
                React.createElement('footer', { className: 'p-10 text-center opacity-30 mt-auto' },
                    React.createElement('p', { className: 'text-[9px] font-black uppercase tracking-[0.4em]' }, 'Super Carnes S.A. © 2026')
                )
            );
        }

        function EmptyState() {
            return React.createElement('div', { className: 'bg-white/5 backdrop-blur-xl rounded-[3rem] p-20 text-center border border-white/10 animate-fade-in' },
                React.createElement('div', { className: 'text-5xl mb-6' }, '🗓️'),
                React.createElement('p', { className: 'text-slate-400 font-bold uppercase tracking-widest text-xs' }, 'No hay eventos activos para esta fecha.')
            );
        }

        function EventCard({ event }) {
            return React.createElement('div', { className: 'group bg-white/5 backdrop-blur-2xl rounded-[2.5rem] border border-white/10 overflow-hidden hover:bg-white/10 transition-all duration-500 hover:scale-[1.02] hover:shadow-2xl hover:border-blue-500/30 p-8' },
                React.createElement('div', { className: 'flex flex-col h-full space-y-6' },
                    React.createElement('div', { className: 'flex justify-between items-start' },
                        React.createElement('div', { className: 'space-y-1' },
                            React.createElement('h2', { className: 'text-2xl font-black text-white uppercase italic tracking-tighter' }, event.nombre),
                            React.createElement('p', { className: 'text-[10px] font-black text-blue-400 uppercase tracking-widest' }, 
                                `${event.fecha_inicio || 'TBA'} — ${event.fecha_fin || 'TBA'}`
                            )
                        ),
                        event.logo_url && React.createElement('img', { src: event.logo_url, className: 'h-12 w-12 rounded-2xl object-cover bg-white ring-4 ring-white/10' })
                    ),
                    React.createElement('div', { className: 'flex items-center space-x-2' },
                        React.createElement('span', { className: 'px-3 py-1 bg-white/5 rounded-lg text-[8px] font-black uppercase tracking-widest text-slate-400' }, 
                            `Votación: ${event.tipo_votacion}`
                        ),
                        React.createElement('span', { className: 'px-3 py-1 bg-blue-500/20 text-blue-400 rounded-lg text-[8px] font-black uppercase tracking-widest' }, 
                            event.estado
                        )
                    ),
                    React.createElement('div', { className: 'pt-2 flex gap-3' },
                        React.createElement('a', { 
                            href: `/c/${event.slug}/registro`,
                            className: 'flex-1 bg-white text-slate-950 px-6 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest text-center hover:bg-blue-500 hover:text-white transition-all shadow-xl shadow-blue-500/10'
                        }, 'Inscripción'),
                        React.createElement('a', { 
                            href: `/resultados/${event.slug}`,
                            className: 'flex-1 bg-white/10 text-white px-6 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest text-center border border-white/10 hover:bg-white/20 transition-all'
                        }, 'Votación Publica')
                    )
                )
            );
        }

        function LandingPage({ events, selectedDate }) {
            const [date, setDate] = useState(selectedDate || '');

            const filtered = useMemo(() => {
                if (!date) return events;
                return events.filter((event) => {
                    const startOk = !event.fecha_inicio || event.fecha_inicio <= date;
                    const endOk = !event.fecha_fin || event.fecha_fin >= date;
                    return startOk && endOk;
                });
            }, [events, date]);

            return React.createElement('section', { className: 'space-y-12 animate-fade-in' },
                React.createElement('div', { className: 'flex flex-col md:flex-row justify-between items-center gap-6 mb-12' },
                    React.createElement('div', { className: 'bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-2 pl-6 flex items-center shadow-2xl' },
                        React.createElement('label', { className: 'text-[9px] font-black uppercase tracking-widest text-slate-500 mr-4 whitespace-nowrap' }, 'Filtrar por Fecha:'),
                        React.createElement('input', {
                            type: 'date',
                            value: date,
                            onChange: (e) => setDate(e.target.value),
                            className: 'bg-transparent text-white border-0 outline-none p-3 font-bold text-sm uppercase'
                        })
                    ),
                    date && React.createElement('button', { 
                        onClick: () => setDate(''),
                        className: 'text-[10px] font-black uppercase tracking-widest text-blue-400 hover:text-white transition-colors underline underline-offset-8'
                    }, 'Ver Todos los Eventos')
                ),
                filtered.length === 0
                    ? React.createElement(EmptyState)
                    : React.createElement('div', { className: 'grid gap-8 md:grid-cols-2 lg:grid-cols-2' },
                        ...filtered.map((event) => React.createElement(EventCard, { key: event.id, event }))
                    )
            );
        }

        function App() {
            const initial = window.__EVENT_APP__ || { events: [], selectedDate: '' };
            const [currentEvent, setCurrentEvent] = useState(null);

            return React.createElement(EventContext.Provider, { value: { currentEvent, setCurrentEvent } },
                React.createElement(EventLayout, null,
                    React.createElement(LandingPage, { events: initial.events, selectedDate: initial.selectedDate })
                )
            );
        }

        const rootEl = document.getElementById('app');
        if (rootEl) createRoot(rootEl).render(React.createElement(App));
    </script>
</body>
</html>
