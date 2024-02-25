<?php

namespace App\Imports;

use App\Jobs\PosangJob;
use App\Models\BatchUser;
use App\Models\nilai;
use App\Models\pesan;
use App\Models\siswa;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

use function PHPUnit\Framework\isEmpty;

class SiswaImport implements WithHeadingRow, WithValidation, ToCollection, WithChunkReading
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
        foreach ($rows as $row) {
            $jobs[] = new PosangJob($row['name'], $row['nisn'], $this->npsn, $row['npsn_smp'], $row['tingkat'], $row['rombel'], $row['nilai'], $this->user_id);
        }
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
            "tingkat" => ['required'],
            "nisn" => ['required', 'numeric'],
            "npsn_smp" => ['required'],
            "nilai" => ['required','numeric', 'max:100' ,'min:0'],
        ];
    }

    public function customValidationAttributes()
    {
        return [
            'name' => 'Nama tidak boleh kosong',
            'tingkat' => 'Tingkat tidak boleh kosong',
            'nisn' => 'nisn tidak boleh kosong / nisn harus angka ',
            "nilai" => "nilai tidak boleh kosong / nilai maksimal 100 minimal 0",
            "npsn_smp" => "npsn smp tidak boleh kosong / npsn smp harus angka",
            "npsn_sma" => "npsn sma tidak boleh kosong / npsn sma harus angka",
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    // public function handle()
    // {
    //     $batch = BatchUser::where([
    //         "user_id" => $this->user_id,
    //     ])->orderBy('id', 'desc')->first();
    //     $batch->finish = 1;
    //     $batch->save();
    // }
}
