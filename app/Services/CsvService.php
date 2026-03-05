<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvService
{
    public function generate(iterable $data, ?string $filename = null): StreamedResponse
    {
        $filename ??= 'resultados_fonda_challenge_' . date('Ymd') . '.csv';

        $callback = function () use ($data): void {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['Posicion', 'Fonda', 'Responsable', 'Plato', 'Promedio Jueces', 'Ajuste Admin', 'Total', 'Votos Publicos']);

            $rows = $data instanceof Collection ? $data->values() : collect($data)->values();

            foreach ($rows as $index => $f) {
                $pos = data_get($f, 'posicion', $index + 1);
                fputcsv($output, [
                    $pos,
                    data_get($f, 'nombre_fonda', ''),
                    data_get($f, 'nombre_persona', ''),
                    data_get($f, 'plato_preparar', ''),
                    data_get($f, 'judge_avg', data_get($f, 'promedio', 0)),
                    data_get($f, 'ajuste_admin', 0),
                    data_get($f, 'final_score', data_get($f, 'puntaje_final', 0)),
                    data_get($f, 'public_votes_count', 0),
                ]);
            }
            fclose($output);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
