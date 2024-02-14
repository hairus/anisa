<?php

namespace App\Imports;

use App\Jobs\PosangJob;
use App\Models\BatchUser;
use App\Models\nilai;
use App\Models\pesan;
use App\Models\siswa;
use Illuminate\Contracts\Queue\ShouldQueue;
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
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    public function collection(Collection $rows)
    {
        $jobs = [];
        foreach ($rows as $row) {
            $jobs[] = new PosangJob($row['name'], $row['nisn'], $row['npsn_sma'], $row['npsn_smp'], $row['tingkat'], $row['rombel'], $row['nilai'], $this->user_id);
        }
        $batch = Bus::batch($jobs)->dispatch();
        BatchUser::create([
            "user_id" => $this->user_id,
            "batch_id" => $batch->id
        ]);
        // $user = BatchUser::find($this->user_id);
        // if($user){
        //    $user->user_id = $this->user_id;
        //    $user->batch_id = $batch->id;
        //    $user->save();
        // }else{
        //     BatchUser::create([
        //         "user_id" => $this->user_id,
        //         "batch_id" => $batch->id
        //     ]);
        // }
    }


    public function rules(): array
    {
        return [
            "name" => ['required'],
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

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
