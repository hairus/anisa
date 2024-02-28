<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiswaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "name" => $this->name,
            "nisn" => $this->nisn,
            "npsn_sma" => $this->npsn_sma,
            "npsn_smp" => $this->npsn_smp,
            "npsn_smp" => $this->npsn_smp,
            "tingkat" => $this->tingkat,
            "tingkat" => $this->tingkat,
            "rombel" => $this->rombel,
            "smas" => $this->smas->nm_sekolah,
            "smps" => $this->smps->nama_smp,
            "nilai" => $this->nilai->nilai,
        ];
    }
}
