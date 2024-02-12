<?php

namespace App\Exports;

use App\Models\upload;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UploadsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return upload::all();
    }

    public function headings(): array
    {
        return ["id","nama", "nis", "nilai"];
    }
}
