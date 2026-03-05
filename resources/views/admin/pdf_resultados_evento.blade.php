<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resultados Finales</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #f3f4f6; }
    </style>
</head>
<body>
    <h2>Resultados Finales - {{ $event?->nombre ?? 'General' }}</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Participante</th>
                <th>Responsable</th>
                <th>Prom. Jueces</th>
                <th>Ajuste</th>
                <th>Final</th>
                <th>Votos Públicos</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ranking as $row)
                <tr>
                    <td>{{ $row['posicion'] }}</td>
                    <td>{{ $row['nombre_fonda'] }}</td>
                    <td>{{ $row['nombre_persona'] }}</td>
                    <td>{{ $row['judge_avg'] }}</td>
                    <td>{{ $row['ajuste_admin'] }}</td>
                    <td>{{ $row['final_score'] }}</td>
                    <td>{{ $row['public_votes_count'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
