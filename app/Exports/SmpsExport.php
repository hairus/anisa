<?php

namespace App\Exports;

use App\Models\smps;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SmpsExport implements WithHeadings, WithMapping, FromQuery
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return[
            'npsn smp',
            'nama smp',
            'kab kota',
            'jenjang',
            'kab kota'
        ];
    }

    public function map($smps): array
    {
        return[
            $smps->npsn_smp,
            $smps->nama_smp,
            $smps->kabs->kab_kota,
            $smps->jenjang,
            $smps->kabs->kab_kota,
        ];
    }

    public function query()
    {
        return smps::where('kab_id', '<>', 99);
    }
}
