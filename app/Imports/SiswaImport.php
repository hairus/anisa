<?php

namespace App\Imports;

use App\Models\siswa;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SiswaImport implements ToCollection, WithChunkReading, ShouldQueue, WithBatchInserts, WithStartRow
{
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            siswa::create([
                "name" => $row[0],
                "nisn" => trim(strtoupper($row[1])),
                "npsn_sma" => trim($row[2]),
                "npsn_smp" => trim($row[3]),
                "tingkat" => $row[4],
                "rombel" => $row[5],
                "user_id" => $this->user_id,
            ])->nilai()->create([
                'npsn_sma' => trim($row[2]),

                'npsn_smp' => trim($row[3]),

                'nilai' => $row[6],
            ]);
        }
    }

    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
