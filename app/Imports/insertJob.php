<?php

namespace App\Imports;

use App\Jobs\UploadJobs;
use App\Models\BatchUser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class insertJob implements WithHeadingRow, WithValidation, ToCollection, WithChunkReading
{
    use Importable;

    public $user_id;
    public $npsn;


    public function __construct($user_id, $npsn)
    {
        $this->user_id = $user_id;
        $this->npsn = $npsn;

    }

    public function collection(Collection $rows)
    {
        $jobs = [];
        $rows->chunk(500)->each(function($chunk) use (&$jobs) {
            $processedData = $chunk->map(function($row) {
                return [
                    'name' => strtoupper($row['name']),
                    'nisn' => $row['nisn'],
                    'npsn_smp' => $row['npsn_smp'],
                    'tingkat' => $row['tingkat'],
                    'rombel' => strtoupper($row['rombel']),
                    'nilai' => $row['nilai'],
                    'user_id' => $this->user_id,
                    'nama_smp' => $row['nama_smp']
                ];
            });

            $jobs[] = new UploadJobs(
                $processedData->toArray(), // Perubahan utama di sini
                $this->npsn
            );

        });

        $batch = Bus::batch($jobs)->dispatch();
        BatchUser::create([
            "user_id" => $this->user_id,
            "batch_id" => $batch->id
        ]);
    }


    public function rules(): array
    {
        return [
            "name" => ['required'],
            "nama_smp" => ['required'],
            "tingkat" => ['required', 'numeric', 'min:10', 'max:12'],
            "nisn" => ['required', 'numeric', 'regex:/^\d{10}$/'],
            "rombel" => ['required'],
            "npsn_smp" => ['required','regex:/^([1-9A-Z]\d{7}|[1-9A-Z]LN\d{5})$/'],
            "nilai" => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }

    public function customValidationAttributes()
    {
        return [
            'name' => 'Nama tidak boleh kosong',
            'tingkat' => 'Tingkat tidak boleh kosong / tingkat harus angka contoh 10,11,12/ minimal kelas 10, maksimal kelas 12',
            'nisn' => 'Format nisn salah atau tidak boleh kosong',
            "nilai" => "Nilai tidak boleh kosong / nilai maksimal 100 minimal 0",
            "rombel" => "Rombel tidak boleh kosong",
            "npsn_smp" => "Npsn smp salah atau tidak boleh kosong",
            "nama_smp" => "Nama smp tidak boleh kosong",
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
