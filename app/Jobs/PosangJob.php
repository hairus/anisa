<?php

namespace App\Jobs;

use App\Models\pesan;
use App\Models\siswa;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PosangJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

     public  $name;
     public  $npsn_sma;
     public  $nisn;
     public  $tingkat;
     public  $npsn_smp;
     public  $rombel;
     public  $nilai;
     public $user_id;


    public function __construct($name, $nisn, $npsn_sma, $npsn_smp, $tingkat,$rombel, $nilai, $user_id)
    {
        $this->name = $name;
        $this->nisn = $nisn;
        $this->npsn_sma = $npsn_sma;
        $this->npsn_smp = $npsn_smp;
        $this->tingkat = $tingkat;
        $this->rombel = $rombel;
        $this->nilai = $nilai;
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        siswa::create([
            "name" => $this->name,
            "nisn" => $this->nisn,
            "npsn_sma" => $this->npsn_sma,
            "npsn_smp" => $this->npsn_smp,
            "tingkat" => $this->tingkat,
            "rombel" => $this->rombel,
            "user_id" => $this->user_id,
        ])->nilai()->create([
            "npsn_sma" => $this->npsn_sma,
            "npsn_smp" => $this->npsn_smp,
            "nilai" => $this->nilai,
        ]);
    }
}
