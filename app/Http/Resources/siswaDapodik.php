<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class siswaDapodik extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->nama,
            "nisn" => $this->nisn,
            "jenjang" => $this->jenjang,
            "npsn_sekolah_sekarang" => $this->npsn_sekolah_sekarang,
            "sma" => $this->smas->nm_sekolah,
            "tingkat" => $this->tingkat,
            "rombel" => $this->rombel,
            "smp" => $this->sekolah_smp,
            "nilai" => $this->nilai ? $this->nilai->nilai : 0,
        ];
    }
}
