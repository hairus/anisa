<?php

namespace App\Exports;

use App\Models\siswa;
use App\Models\siswaDapodik;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class masterSiswa implements WithHeadings, WithMapping, FromQuery
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
            'nama',
            'nisn',
            'rombel',
            "tingkat",
            'sekolah_smp'
        ];
    }

    public function map($student): array
    {
        return[
            $student->nama,
            $student->nisn,
            $student->rombel,
            $student->tingkat,
            $student->sekolah_smp ? $student->sekolah_smp : '-',
        ];
    }

    public function query()
    {
        return siswaDapodik::where('npsn_sekolah_sekarang',$this->user_id)
            ->orderBy('tingkat')
            ->orderBy('rombel')
            ->orderBy('nama');
    }
}
