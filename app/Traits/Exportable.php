<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;

trait Exportable
{
    public function exportToCSV(Collection $data, array $headers, string $filename): Response
    {
        $output = fopen('php://temp', 'w');

        fwrite($output, "\xEF\xBB\xBF");

        fputcsv($output, $headers);

        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
        ]);

    }
}
