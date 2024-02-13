<?php

namespace App\Imports;

use App\Models\nilai;
use App\Models\pesan;
use App\Models\siswa;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

use function PHPUnit\Framework\isEmpty;

class SiswaImport implements WithHeadingRow, WithValidation, ToCollection, WithChunkReading
{
    use Importable;
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            siswa::create([

                'name' => strtoupper(trim($row['name'])),

                'npsn_sma' => trim($row['npsn_sma']),

                'nisn' => trim($row['nisn']),

                'tingkat' => ucwords(strtolower(trim($row['tingkat']))),

                'npsn_smp' => trim(strtoupper($row['npsn_smp'])),

                'rombel' => $row['rombel'],

                "user_id" => $this->user_id

            ])->nilai()->create([
                'npsn_sma' => trim($row['npsn_sma']),

                'npsn_smp' => trim(strtoupper($row['npsn_smp'])),

                'nilai' => $row['nilai'],
            ]);
        }
    }


    public function rules(): array
    {
        return [
            "nisn" => ['required'],
            "npsn_sma" => ['required'],
            "npsn_smp" => ['required'],
            "nilai" => ['required', 'max:100' ,'min:0'],
        ];
    }

    public function customValidationAttributes()
    {
        return [
            'nisn' => 'nisn tidak boleh kosong',
            "nilai" => "nilai tidak boleh kosong",
            "npsn_sma" => "npsn smp tidak boleh kosong",
            "npsn_smp" => "npsn sma tidak boleh kosong",
        ];
    }

    // public function onFailure(Failure ...$failures)
    // {
    //     // Handle kesalahan validasi jika diperlukan
    // }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function onFailure(Failure $failures)
    {
        pesan::create([
            "user_id" => $this->user_id,
            "pesan" => $failures,
        ]);
    }

    // public function batchSize(): int
    // {
    //     return 1000;
    // }
}
