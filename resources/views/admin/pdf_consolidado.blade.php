<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 1cm; }
        body { font-family: 'Helvetica', sans-serif; font-size: 10px; color: #1a202c; }
        
        /* Encabezado Profesional */
        .header { text-align: center; margin-bottom: 25px; }
        .logo-text { color: #004691; font-size: 24px; font-weight: bold; margin: 0; }
        .sub-title { color: #64748b; font-size: 10px; text-transform: uppercase; letter-spacing: 2px; font-weight: bold; }
        .report-name { margin-top: 15px; font-size: 14px; background: #f1f5f9; padding: 8px; border-radius: 4px; color: #334155; }

        /* Tabla Estilizada */
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th { background-color: #004691; color: white; padding: 12px 6px; text-transform: uppercase; font-size: 9px; border: none; }
        td { padding: 10px 6px; border-bottom: 1px solid #e2e8f0; text-align: center; }
        
        /* Columnas Especiales */
        .fonda-col { text-align: left; width: 200px; border-left: 4px solid #e2e8f0; padding-left: 12px; }
        .fonda-name { font-weight: bold; font-size: 11px; color: #004691; display: block; }
        .fonda-sub { font-size: 8px; color: #94a3b8; text-transform: uppercase; }
        
        .total-col { background-color: #f8fafc; font-weight: bold; font-size: 13px; color: #004691; width: 70px; border-left: 2px solid #cbd5e1; }
        
        /* Indicadores de Ranking */
        .rank-1 { border-left-color: #fbbf24; } /* Oro */
        .rank-2 { border-left-color: #94a3b8; } /* Plata */
        .rank-3 { border-left-color: #b45309; } /* Bronce */

        .puntos { font-weight: 600; color: #334155; }
        .pendiente { color: #cbd5e1; font-style: italic; }

        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 8px; color: #94a3b8; padding-top: 10px; border-top: 1px solid #f1f5f9; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-text">SUPER CARNES</div>
        <div class="sub-title">Fonda Challenge 2026 - Mil Polleras Las Tablas</div>
        <div class="report-name">Lista Oficial de Resultados</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 200px;">Fonda / Participante</th>
                @foreach($jurados as $jurado)
                    <th>{{ $jurado->name }}</th>
                @endforeach
                <th class="total-col" style="background-color: #0f172a; color: white;">Total Final</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fondas as $index => $fonda)
            @php
                $puntosTotal = $fonda->evaluaciones->sum('puntaje');
                $rankClass = '';
                if($index == 0 && $puntosTotal > 0) $rankClass = 'rank-1';
                elseif($index == 1 && $puntosTotal > 0) $rankClass = 'rank-2';
                elseif($index == 2 && $puntosTotal > 0) $rankClass = 'rank-3';
            @endphp
            <tr>
                <td class="fonda-col {{ $rankClass }}">
                    <span class="fonda-name">{{ $fonda->nombre_fonda }}</span>
                    <span class="fonda-sub">{{ $fonda->nombre_persona ?? $fonda->nombre_responsable }}</span>
                </td>
                
                @foreach($jurados as $jurado)
                    @php
                        $puntos = $fonda->evaluaciones->where('user_id', $jurado->id)->sum('puntaje');
                    @endphp
                    <td>
                        @if($puntos > 0)
                            <span class="puntos">{{ $puntos }}</span>
                        @else
                            <span class="pendiente">Pnd.</span>
                        @endif
                    </td>
                @endforeach

                <td class="total-col">
                    {{ $puntosTotal }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Este documento es un reporte oficial generado por el Sistema de Evaluación Super Carnes. 
        Fecha: {{ date('d/m/Y H:i') }} - Página 1 de 1
    </div>
</body>
</html>