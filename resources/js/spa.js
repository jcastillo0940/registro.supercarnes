import React from 'https://esm.sh/react@18';
import { createRoot } from 'https://esm.sh/react-dom@18/client';

const page = window.__APP_PAGE__ || 'home';
const props = window.__APP_PROPS__ || {};

const el = React.createElement;

function layout(title, content) {
  return el('div', { className: 'max-w-6xl mx-auto p-6 space-y-4' },
    el('h1', { className: 'text-2xl font-bold' }, title),
    content
  );
}

function listTable(headers, rows) {
  return el('div', { className: 'bg-white rounded shadow overflow-auto' },
    el('table', { className: 'w-full text-sm' },
      el('thead', null, el('tr', { className: 'border-b' }, ...headers.map(h => el('th', { key: h, className: 'p-2 text-left' }, h)))),
      el('tbody', null, ...rows)
    )
  );
}

function App() {
  switch (page) {
    case 'auth.login':
      return layout('Login', el('form', { method: 'POST', action: '/login', className: 'bg-white p-4 rounded shadow space-y-2 max-w-md' },
        el('input', { type: 'hidden', name: '_token', value: props.csrf }),
        el('input', { name: 'email', placeholder: 'Email', className: 'w-full border rounded p-2' }),
        el('input', { name: 'password', type: 'password', placeholder: 'Password', className: 'w-full border rounded p-2' }),
        el('button', { className: 'bg-blue-600 text-white px-3 py-2 rounded' }, 'Entrar')
      ));

    case 'landing':
      return layout('Landing de Eventos', el('div', { className: 'grid gap-3 md:grid-cols-2' }, ...(props.events || []).map(ev =>
        el('div', { key: ev.id, className: 'bg-white p-4 rounded shadow' },
          el('div', { className: 'font-semibold' }, ev.nombre),
          el('a', { href: `/${ev.slug}/registro`, className: 'text-blue-600 underline text-sm' }, 'Ir al registro')
        )
      )));

    case 'fonda.register':
      return layout('Registro general', el('form', { method: 'POST', action: '/register-fonda', className: 'bg-white p-4 rounded shadow space-y-2' },
        el('input', { type: 'hidden', name: '_token', value: props.csrf }),
        ...['nombre_persona', 'cedula', 'telefono', 'nombre_fonda', 'ubicacion', 'plato_preparar'].map(n => el('input', { key: n, name: n, placeholder: n, className: 'w-full border rounded p-2' })),
        el('button', { className: 'bg-blue-600 text-white px-3 py-2 rounded' }, 'Registrar')
      ));

    case 'fonda.success':
      return layout('Registro exitoso', el('div', { className: 'bg-white rounded shadow p-4' },
        el('p', null, `Participante: ${props.fonda?.nombre_fonda || ''}`),
        props.fonda?.qr_code ? el('img', { src: '/' + props.fonda.qr_code, className: 'h-40 mt-2' }) : null
      ));

    case 'participants.register':
      return layout(`Registro ${props.event?.nombre || ''}`, el('form', { method: 'POST', action: `/${props.event?.slug}/registro`, className: 'bg-white p-4 rounded shadow space-y-2' },
        el('input', { type: 'hidden', name: '_token', value: props.csrf }),
        ...['nombre_persona', 'cedula', 'telefono', 'nombre_fonda', 'ubicacion', 'plato_preparar'].map(n => el('input', { key: n, name: n, placeholder: n, className: 'w-full border rounded p-2' })),
        props.event?.tipo_evento === 'rock_fest' ? el('input', { name: 'nombre_banda', placeholder: 'Nombre de Banda', className: 'w-full border rounded p-2' }) : null,
        props.event?.tipo_evento === 'bbq_challenge' ? el('input', { name: 'tipo_corte', placeholder: 'Tipo de Corte', className: 'w-full border rounded p-2' }) : null,
        el('button', { className: 'bg-blue-600 text-white px-3 py-2 rounded' }, 'Enviar')
      ));

    case 'jurado.index': {
      const rows = (props.initialState?.participants || []).map((p, i) => el('tr', { key: p.id, className: 'border-b' },
        el('td', { className: 'p-2' }, i + 1),
        el('td', { className: 'p-2' }, p.nombre_fonda),
        el('td', { className: 'p-2' }, p.plato_preparar),
        el('td', { className: 'p-2' }, el('a', { href: `/evaluar/${p.uuid}`, className: 'text-blue-600 underline' }, 'Evaluar'))
      ));
      return layout('Panel jurado', listTable(['#', 'Participante', 'Plato', 'Acción'], rows));
    }

    case 'jurado.scanner':
      return layout('Escáner', el('div', { className: 'bg-white p-4 rounded shadow' }, 'Usar escáner QR del navegador (pendiente integración cámara nativa).'));

    case 'fonda.evaluar':
      return layout(`Evaluar ${props.fonda?.nombre_fonda || ''}`, el('form', { method: 'POST', action: `/evaluar/${props.fonda?.uuid}`, className: 'bg-white p-4 rounded shadow space-y-2' },
        el('input', { type: 'hidden', name: '_token', value: props.csrf }),
        ...(props.criterios || []).map(c => el('div', { key: c.id },
          el('label', { className: 'font-semibold block' }, c.nombre),
          el('div', { className: 'grid grid-cols-5 gap-2' }, ...[1,2,3,4,5].map(i => el('label', { key: i, className: 'border rounded p-1 text-center' },
            el('input', { type: 'radio', name: `puntos[${c.id}]`, value: i, required: true }), ` ${i}`
          )))
        )),
        el('textarea', { name: 'notas', className: 'w-full border rounded p-2', placeholder: 'Notas' }),
        el('button', { className: 'bg-blue-600 text-white px-3 py-2 rounded' }, 'Guardar')
      ));

    case 'public.vote':
      return layout(`Voto público - ${props.participant?.nombre_fonda || ''}`, el('form', { method: 'POST', action: `/votar/${props.participant?.uuid}`, className: 'bg-white p-4 rounded shadow space-y-2' },
        el('input', { type: 'hidden', name: '_token', value: props.csrf }),
        ...(props.criterios || []).map(c => el('div', { key: c.id },
          el('label', { className: 'font-semibold block' }, c.nombre),
          el('div', { className: 'grid grid-cols-5 gap-2' }, ...[1,2,3,4,5].map(i => el('label', { key: i, className: 'border rounded p-1 text-center' },
            el('input', { type: 'radio', name: `puntos[${c.id}]`, value: i, required: true }), ` ${i}`
          )))
        )),
        el('button', { className: 'bg-blue-600 text-white px-3 py-2 rounded' }, 'Votar')
      ));

    case 'admin.dashboard': {
      const rows = (props.fondas || []).map((r, i) => el('tr', { key: r.id, className: 'border-b' },
        el('td', { className: 'p-2' }, i + 1),
        el('td', { className: 'p-2' }, r.nombre_fonda),
        el('td', { className: 'p-2' }, Number(r.judge_avg || 0).toFixed(1)),
        el('td', { className: 'p-2' }, Number(r.final_score || 0).toFixed(1))
      ));
      return layout('Admin Dashboard', listTable(['#', 'Fonda', 'Promedio', 'Final'], rows));
    }

    case 'admin.participantes':
      return layout('Admin Participantes', el('pre', { className: 'bg-white p-4 rounded shadow overflow-auto text-xs' }, JSON.stringify(props.participantes || [], null, 2)));
    case 'admin.usuarios':
      return layout('Admin Usuarios', el('pre', { className: 'bg-white p-4 rounded shadow overflow-auto text-xs' }, JSON.stringify(props.usuarios || [], null, 2)));
    case 'admin.logistica':
      return layout('Admin Logística', el('pre', { className: 'bg-white p-4 rounded shadow overflow-auto text-xs' }, JSON.stringify(props.participantes || [], null, 2)));
    case 'admin.criterios':
      return layout('Admin Criterios', el('pre', { className: 'bg-white p-4 rounded shadow overflow-auto text-xs' }, JSON.stringify({ eventos: props.events, criterios: props.criterios }, null, 2)));
    case 'admin.votaciones_jurado':
      return layout('Admin Votaciones', el('pre', { className: 'bg-white p-4 rounded shadow overflow-auto text-xs' }, JSON.stringify({ jurados: props.jurados, votaciones: props.votaciones }, null, 2)));
    case 'admin.consolidado':
      return layout('Admin Consolidado', el('pre', { className: 'bg-white p-4 rounded shadow overflow-auto text-xs' }, JSON.stringify({ jurados: props.jurados, fondas: props.fondas }, null, 2)));

    case 'admin.events.index':
      return layout('Eventos', el('pre', { className: 'bg-white p-4 rounded shadow overflow-auto text-xs' }, JSON.stringify(props.events, null, 2)));
    case 'admin.events.create':
    case 'admin.events.edit':
      return layout(page === 'admin.events.create' ? 'Crear Evento' : 'Editar Evento', el('div', { className: 'bg-white p-4 rounded shadow' }, 'Formulario de evento en React (usar endpoint admin/events).'));

    case 'results.index':
      return layout('Resultados', el('pre', { className: 'bg-white p-4 rounded shadow overflow-auto text-xs' }, JSON.stringify(props.initialState || {}, null, 2)));

    default:
      return layout('Sistema', el('div', { className: 'bg-white p-4 rounded shadow' }, `Página no implementada: ${page}`));
  }
}

createRoot(document.getElementById('app')).render(el(App));
