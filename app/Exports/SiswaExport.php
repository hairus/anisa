<?php

namespace App\Exports;

use App\Models\siswa;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SiswaExport implements WithHeadings, WithMapping, FromQuery
{
    use Exportable;

    public $user_id;

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    public function headings(): array
    {
        return[
            'name',
            'nisn',
            'npsn_smp',
            'tingkat',
            'rombel',
            'nilai',
        ];
    }

    public function map($student): array
    {
        return[
            $student->name,
            $student->nisn,
            $student->npsn_smp,
            $student->tingkat,
            $student->rombel,
            $student->nilai->nilai,
        ];
    }

    public function query()
    {
        return siswa::with('nilai')->where('user_id',$this->user_id);
    }
}
