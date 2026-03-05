<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 1cm; }
        body { font-family: 'Helvetica', sans-serif; font-size: 10px; color: #002d5a; }
        
        /* Premium Header */
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #002d5a; padding-bottom: 15px; }
        .brand { color: #002d5a; font-size: 28px; font-weight: 900; margin: 0; text-transform: uppercase; letter-spacing: -1px; }
        .event { color: #64748b; font-size: 9px; text-transform: uppercase; letter-spacing: 3px; font-weight: bold; margin-top: 5px; }
        .title { margin-top: 15px; font-size: 14px; color: #002d5a; font-weight: 900; text-transform: uppercase; }

        /* Elegant Table */
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th { background-color: #002d5a; color: white; padding: 12px 10px; text-transform: uppercase; font-size: 8px; font-weight: 900; }
        td { padding: 12px 10px; border-bottom: 1px solid #f1f5f9; text-align: left; vertical-align: top; }
        
        /* Column Styling */
        .rank-col { width: 30px; text-align: center; font-weight: 900; color: #94a3b8; }
        .participant-col { width: 180px; }
        .participant-name { font-weight: 900; font-size: 11px; color: #002d5a; display: block; text-transform: uppercase; }
        .participant-sub { font-size: 8px; color: #64748b; font-weight: 700; text-transform: uppercase; }
        
        .propuesta-col { width: 140px; font-style: italic; color: #475569; }
        .contact-col { width: 100px; font-size: 9px; }
        .contact-label { font-weight: 900; color: #94a3b8; font-size: 7px; text-transform: uppercase; display: block; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 7px; color: #94a3b8; padding-top: 15px; border-top: 1px solid #f1f5f9; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">SUPER CARNES</div>
        <div class="event">Certificación Oficial del Evento 2026</div>
        <div class="title">Hoja de Ruta y Lista de Participantes</div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="rank-col">#</th>
                <th class="participant-col">Participante</th>
                <th class="propuesta-col">Propuesta / Plato</th>
                <th class="contact-col">Responsable</th>
                <th style="width: 150px;">Ubicación Exacta</th>
            </tr>
        </thead>
        <tbody>
            @foreach($participantes->sortBy('orden_visita') as $index => $p)
            <tr>
                <td class="rank-col">{{ $p->orden_visita ?: $index + 1 }}</td>
                <td class="participant-col">
                    <span class="participant-name">{{ $p->nombre_fonda }}</span>
                    <span class="participant-sub">ID: {{ $p->cedula }}</span>
                </td>
                <td class="propuesta-col">{{ $p->plato_preparar }}</td>
                <td class="contact-col">
                    <span class="contact-label">Encargado:</span>
                    <div style="font-weight: 700;">{{ $p->nombre_persona }}</div>
                    <span class="contact-label" style="margin-top: 5px;">Teléfono:</span>
                    <div>{{ $p->telefono }}</div>
                </td>
                <td style="font-size: 9px; line-height: 1.4;">
                    {{ $p->ubicacion }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Este documento es para uso exclusivo del personal de logística y jurado calificador de Super Carnes.<br>
        Generado el {{ date('d/m/Y \a \l\a\s H:i') }} - Hoja de Control de Operaciones
    </div>
</body>
</html>