<?php

namespace App\Jobs;


use App\Models\siswa;
use App\Models\siswaDapodik;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        DB::connection()->disableQueryLog();
        Model::unguard();

        $chunkSize = 500; // Sesuaikan dengan kapasitas server

        try {
            siswaDapodik::where('npsn_sekolah_sekarang', $this->npsn)
                ->chunkById($chunkSize, function ($siswas) {
                    $siswaBatch = [];
                    $nilaiBatch = [];

                    // Siapkan data siswa
                    foreach ($siswas as $data) {
                        $siswaBatch[] = [
                            "name" => $data->nama,
                            "nisn" => $data->nisn,
                            "npsn_sma" => $this->npsn,
                            "npsn_smp" => 0,
                            "tingkat" => $data->tingkat,
                            "rombel" => $data->rombel,
                            "user_id" => $this->user_id,
                            "nama_smp" => $data->sekolah_smp ?: "-",
                            "created_at" => now(),
                            "updated_at" => now()
                        ];
                    }

                    // Proses dalam transaction
                    DB::transaction(function () use ($siswaBatch, &$nilaiBatch) {
                        // Insert siswa dan ambil ID pertama
                        DB::table('siswas')->insert($siswaBatch);
                        $firstId = DB::getPdo()->lastInsertId();

                        // Generate data nilai
                        $totalRecords = count($siswaBatch);
                        $nilaiBatch = [];

                        for ($i = 0; $i < $totalRecords; $i++) {
                            $nilaiBatch[] = [
                                'siswa_id' => $firstId + $i,
                                'nilai' => 0,
                                'npsn_smp' => 0,
                                'npsn_sma' => $this->npsn,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                        }

                        // Insert nilai
                        DB::table('nilais')->insert($nilaiBatch);
                    });

                    Log::info("Berhasil memproses " . count($siswaBatch) . " records");
                }, $column = 'id'); // Kolom unik untuk chunking

        } catch (\Exception $e) {
            Log::error("Error processing job: " . $e->getMessage());
            $this->fail($e);
        } finally {
            Model::reguard();
        }
    }
    public function middleware()
    {
        // Menambahkan middleware untuk mencegah overlap
        return [new \Illuminate\Queue\Middleware\WithoutOverlapping($this->job->getJobId())];
    }
}
