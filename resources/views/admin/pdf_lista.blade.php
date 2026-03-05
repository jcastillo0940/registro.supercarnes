<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #004691; padding-bottom: 10px; }
        .logo-text { color: #004691; font-size: 24px; font-weight: bold; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #004691; color: white; padding: 8px; text-transform: uppercase; font-size: 10px; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-text">Super Carnes - Fonda Challenge 2026</div>
        <p>Lista Oficial de Participantes para Calificación</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Fonda</th>
                <th>Plato a Preparar</th>
                <th>Responsable</th>
                <th>Cédula</th>
                <th>Teléfono</th>
                <th>Ubicación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($participantes as $index => $p)
            <tr>
                <td style="font-weight: bold;">{{ $index + 1 }}</td>
                <td style="color: #004691; font-weight: bold;">{{ $p->nombre_fonda }}</td>
                <td>{{ $p->plato_preparar }}</td>
                <td>{{ $p->nombre_persona }}</td>
                <td>{{ $p->cedula }}</td>
                <td>{{ $p->telefono }}</td>
                <td style="font-style: italic;">{{ $p->ubicacion }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generado el {{ date('d/m/Y H:i') }} - Sistema de Evaluación Super Carnes
    </div>
</body>
</html>