<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Calendario de Eventos</title>
    <script>
        window.__EVENT_APP__ = @json($initialState);
    </script>
    @vite(['resources/css/app.css'])
</head>
<body>
    <div id="app"></div>

    <script type="module">
        import React, { createContext, useContext, useMemo, useState } from 'https://esm.sh/react@18';
        import { createRoot } from 'https://esm.sh/react-dom@18/client';

        const EventContext = createContext({ currentEvent: null, setCurrentEvent: () => {} });

        function EventLayout({ children }) {
            const { currentEvent } = useContext(EventContext);
            const cssVars = {
                '--event-primary': currentEvent?.color_primario || '#1d4ed8',
                '--event-secondary': currentEvent?.color_secundario || '#0f172a',
            };

            return React.createElement('div', { style: cssVars, className: 'min-h-screen bg-slate-50 text-slate-900' },
                React.createElement('header', { className: 'p-6 text-white', style: { background: 'var(--event-primary)' } },
                    React.createElement('div', { className: 'mx-auto max-w-5xl flex items-center gap-4' },
                        currentEvent?.logo_url ? React.createElement('img', { src: currentEvent.logo_url, className: 'h-12 w-12 rounded object-cover bg-white' }) : null,
                        React.createElement('div', null,
                            React.createElement('h1', { className: 'text-2xl font-bold' }, 'Calendario de Eventos'),
                            React.createElement('p', { className: 'text-sm opacity-90' }, currentEvent?.nombre || 'Selecciona un evento')
                        )
                    )
                ),
                React.createElement('main', { className: 'mx-auto max-w-5xl p-6' }, children)
            );
        }

        function Proximamente() {
            return React.createElement('div', { className: 'rounded-xl border bg-white p-8 text-center text-lg font-semibold' }, 'Próximamente: no hay eventos activos.');
        }

        function LandingPage({ events, selectedDate }) {
            const [date, setDate] = useState(selectedDate || '');
            const { setCurrentEvent } = useContext(EventContext);

            const filtered = useMemo(() => {
                if (!date) return events;
                return events.filter((event) => {
                    const startOk = !event.fecha_inicio || event.fecha_inicio <= date;
                    const endOk = !event.fecha_fin || event.fecha_fin >= date;
                    return startOk && endOk;
                });
            }, [events, date]);

            const activeEvents = filtered.filter((event) => event.estado === 'activo');

            return React.createElement('section', { className: 'space-y-6' },
                React.createElement('div', { className: 'rounded-xl bg-white p-4 border' },
                    React.createElement('label', { className: 'block text-sm font-medium mb-2' }, 'Filtrar por fecha'),
                    React.createElement('input', {
                        type: 'date',
                        value: date,
                        onChange: (e) => setDate(e.target.value),
                        className: 'border rounded px-3 py-2'
                    })
                ),
                activeEvents.length === 0
                    ? React.createElement(Proximamente)
                    : React.createElement('div', { className: 'grid gap-4 md:grid-cols-2' },
                        ...activeEvents.map((event) => React.createElement('article', { key: event.id, className: 'rounded-xl border bg-white p-5 shadow-sm' },
                            React.createElement('div', { className: 'flex items-center justify-between mb-2' },
                                React.createElement('h2', { className: 'text-lg font-bold' }, event.nombre),
                                React.createElement('button', {
                                    className: 'text-sm px-3 py-1 rounded text-white',
                                    style: { background: 'var(--event-secondary)' },
                                    onClick: () => setCurrentEvent(event),
                                }, 'Aplicar diseño')
                            ),
                            React.createElement('p', { className: 'text-sm' }, `${event.fecha_inicio || 'Por definir'} - ${event.fecha_fin || 'Por definir'}`),
                            React.createElement('p', { className: 'text-xs mt-2 uppercase tracking-wide text-slate-500' }, `Tipo de votación: ${event.tipo_votacion}`),
                            React.createElement('a', { href: `/${event.slug}/registro`, className: 'inline-block mt-3 text-sm underline' }, 'Ir al registro')
                        ))
                    )
            );
        }

        function App() {
            const initial = window.__EVENT_APP__ || { events: [], selectedDate: '' };
            const [currentEvent, setCurrentEvent] = useState(initial.events.find((e) => e.estado === 'activo') || null);

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
