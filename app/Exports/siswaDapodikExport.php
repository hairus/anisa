<?php

namespace App\Exports;

use App\Models\siswa;
use App\Models\siswaDapodik;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class siswaDapodikExport implements WithHeadings, WithMapping, FromQuery
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
            'nama_smp',
            'tingkat',
            'rombel',
            'nilai',
        ];
    }

    public function map($student): array
    {
        return[
            $student->nama,
            $student->nisn,
            $student->npsn_smp ? $student->npsn_smp : '-',
            $student->sekolah_smp,
            $student->tingkat,
            $student->rombel,
            $student->nilai ? $student->nilai->nilai : '0',
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
