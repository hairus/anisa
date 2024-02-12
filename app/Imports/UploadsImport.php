<?php

namespace App\Imports;

use App\Models\upload;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class UploadsImport implements ToModel, WithStartRow, WithChunkReading, ShouldQueue,WithBatchInserts
{

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }


    public function model(array $row)
    {
        return new upload([
            'name'     => $row[0],
            'nis'     => $row[1],
            'nilai'     => $row[2],
            'user_id'     => $this->user_id,
        ]);
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
