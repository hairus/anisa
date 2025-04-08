<?php

namespace App\Jobs;

use App\Models\AntrianSiswa;
use App\Models\siswa;
use App\Models\siswaDapodik;
use App\Models\User; // Pastikan untuk mengimpor model yang sesuai
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class InsertDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id;
    public $npsn;

    // Menerima data yang akan disisipkan melalui konstruktor
    public function __construct($user_id, $npsn)
    {
        $this->user_id = $user_id;
        $this->npsn = $npsn;
    }

    /**
     * Eksekusi tugas untuk insert data ke database.
     *
     * @return void
     */
    public function handle()
    {
        $cek = siswa::where('user_id', $this->user_id)->count();
        if($cek > 0){
            $delete = siswa::where('user_id', $this->user_id)->delete();
        }
        $siswas = siswaDapodik::where('npsn_sekolah_sekarang', $this->npsn)->get();
        foreach ($siswas as $data){
            $sim = siswa::create([
                "name" => $data->nama,
                "nisn" => $data->nisn,
                "npsn_sma" => $this->npsn,
                "npsn_smp" => 0,
                "tingkat" => $data->tingkat,
                "rombel" => $data->rombel,
                "user_id" => $this->user_id,
                "nama_smp" => $data->sekolah_smp ? $data->sekolah_smp : "-",
            ])->nilai()->create([
                'nilai' => 0,
                "npsn_smp" => 0,
                "npsn_sma" => $this->npsn,
            ]);
        }
    }
    public function middleware()
    {
        // Menambahkan middleware untuk mencegah overlap
        return [new \Illuminate\Queue\Middleware\WithoutOverlapping($this->job->getJobId())];
    }
}
