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
        .title { margin-top: 15px; font-size: 14px; color: #002d5a; font-weight: 900; text-transform: uppercase; italic: true; }

        /* Elegant Table */
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th { background-color: #002d5a; color: white; padding: 15px 10px; text-transform: uppercase; font-size: 8px; font-weight: 900; letter-spacing: 1px; }
        td { padding: 12px 10px; border-bottom: 1px solid #f1f5f9; text-align: center; }
        
        /* Column Styling */
        .participant-col { text-align: left; width: 220px; padding-left: 0; }
        .participant-name { font-weight: 900; font-size: 11px; color: #002d5a; display: block; text-transform: uppercase; }
        .participant-sub { font-size: 8px; color: #94a3b8; font-weight: bold; text-transform: uppercase; }
        
        .score-col { font-weight: 700; color: #334155; font-size: 10px; }
        .total-col { background-color: #f8fafc; font-weight: 900; font-size: 14px; color: #002d5a; width: 80px; border-left: 2px solid #002d5a; }
        
        /* Rank Styles */
        .rank-1 { background-color: #fffbeb; color: #92400e; }
        .rank-2 { background-color: #f8fafc; color: #475569; }
        .rank-3 { background-color: #fff7ed; color: #9a3412; }

        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 7px; color: #94a3b8; padding-top: 15px; border-top: 1px solid #f1f5f9; }
        .stamp { position: absolute; bottom: 50px; right: 0; border: 2px solid #002d5a; padding: 10px; color: #002d5a; font-weight: 900; text-transform: uppercase; opacity: 0.2; transform: rotate(-15deg); }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">SUPER CARNES</div>
        <div class="event">Certificación Oficial de Resultados 2026</div>
        <div class="title italic">Consolidado General de Votaciones - Jurado Calificador</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 220px;">Participante / Entidad</th>
                @foreach($jurados as $jurado)
                    <th>{{ $jurado->name }}</th>
                @endforeach
                <th class="total-col" style="background-color: #002d5a; color: white;">Puntaje Final</th>
            </tr>
        </thead>
        <tbody>
            @foreach($participants as $index => $participant)
            @php
                $puntosTotal = $participant->evaluaciones->sum('puntaje');
                $rowStyle = '';
                if($index == 0 && $puntosTotal > 0) $rowStyle = 'rank-1';
            @endphp
            <tr class="{{ $rowStyle }}">
                <td class="participant-col">
                    <span class="participant-name">{{ $participant->nombre_fonda }}</span>
                    <span class="participant-sub">{{ $participant->nombre_persona }}</span>
                </td>
                
                @foreach($jurados as $jurado)
                    @php
                        $puntos = $participant->evaluaciones->where('user_id', $jurado->id)->sum('puntaje');
                    @endphp
                    <td class="score-col">
                        @if($puntos > 0)
                            {{ number_format($puntos, 0) }}
                        @else
                            <span style="color: #cbd5e1; font-style: italic;">---</span>
                        @endif
                    </td>
                @endforeach

                <td class="total-col" style="{{ $index == 0 ? 'background-color: #fef3c7;' : '' }}">
                    {{ number_format($puntosTotal, 0) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="stamp">Documento Validado</div>

    <div class="footer">
        Este reporte consolidado es propiedad de Super Carnes S.A. y representa el veredicto final e inapelable del jurado.<br>
        Generado el {{ date('d/m/Y \a \l\a\s H:i') }} - Sistema de Evaluación Eventos v2.0
    </div>
</body>
</html>