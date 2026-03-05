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
        td { padding: 12px 10px; border-bottom: 1px solid #f1f5f9; text-align: center; vertical-align: middle; }
        
        /* Column Styling */
        .rank-col { width: 35px; font-weight: 900; font-size: 14px; color: #94a3b8; }
        .participant-col { width: 200px; text-align: left; }
        .participant-name { font-weight: 900; font-size: 11px; color: #002d5a; display: block; text-transform: uppercase; }
        .participant-sub { font-size: 8px; color: #64748b; font-weight: 700; text-transform: uppercase; }
        
        .score-col { font-weight: 700; color: #475569; }
        .final-col { font-weight: 900; font-size: 15px; color: #002d5a; width: 60px; }
        
        /* Top Rank Badge */
        .rank-1 { background-color: #fffbeb; color: #92400e; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 7px; color: #94a3b8; padding-top: 15px; border-top: 1px solid #f1f5f9; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">SUPER CARNES</div>
        <div class="event">{{ $event?->nombre ?? 'Evento General' }} - Resultados Oficiales</div>
        <div class="title italic">Tabla de Posiciones Final</div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="rank-col">RK</th>
                <th class="participant-col">Participante</th>
                <th class="score-col">Prom. Jueces</th>
                <th class="score-col">Ajuste</th>
                <th class="score-col">Votos Púb.</th>
                <th class="final-col" style="background-color: #002d5a; color: white;">Final</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ranking as $index => $row)
            <tr class="{{ $index == 0 ? 'rank-1' : '' }}">
                <td class="rank-col">{{ $row['posicion'] }}</td>
                <td class="participant-col">
                    <span class="participant-name">{{ $row['nombre_fonda'] }}</span>
                    <span class="participant-sub">{{ $row['nombre_persona'] }}</span>
                </td>
                <td class="score-col">{{ number_format($row['judge_avg'], 2) }}</td>
                <td class="score-col" style="color: {{ $row['ajuste_admin'] > 0 ? '#16a34a' : ($row['ajuste_admin'] < 0 ? '#dc2626' : '#94a3b8') }}">
                    {{ $row['ajuste_admin'] > 0 ? '+' : '' }}{{ $row['ajuste_admin'] }}
                </td>
                <td class="score-col">{{ $row['public_votes_count'] }}</td>
                <td class="final-col">
                    {{ number_format($row['final_score'], 1) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Certificación generada automáticamente por el Sistema de Resultados Super Carnes.<br>
        Fecha: {{ date('d/m/Y \a \l\a\s H:i') }} - ID Reporte: {{ uniqid() }}
    </div>
</body>
</html>
