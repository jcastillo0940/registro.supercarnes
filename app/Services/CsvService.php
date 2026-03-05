<?php
namespace App\Services;

class CsvService {
    public function generate($data) {
        $filename = "resultados_fonda_challenge_" . date('Ymd') . ".csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Posicion', 'Fonda', 'Responsable', 'Plato', 'Promedio Jueces', 'Ajuste Admin', 'Total']);
        
        $pos = 1;
        foreach ($data as $f) {
            fputcsv($output, [
                $pos++,
                $f->nombre_fonda,
                $f->nombre_persona,
                $f->plato_preparar,
                $f->promedio,
                $f->ajuste_admin,
                $f->puntaje_final
            ]);
        }
        fclose($output);
    }
}