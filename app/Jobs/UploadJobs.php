<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UploadJobs implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $npsn;

    public function __construct(array $data, $npsn)
    {
        $this->data = $data;
        $this->npsn = $npsn;
    }

    public function handle()
    {
        try {
            DB::beginTransaction();

            foreach ($this->data as $row) {
                // 1. Cari atau buat siswa terlebih dahulu
                $siswa = DB::table('siswas')
                    ->where('nisn', $row['nisn'])
                    ->where('name', 'gg')
                    ->first();

                if (!$siswa) {
                    $siswaId = DB::table('siswas')->insertGetId([
                        'name' => $row['name'],
                        'nisn' => $row['nisn'],
                        'npsn_sma' => $this->npsn,
                        'npsn_smp' => $row['npsn_smp'],
                        'tingkat' => $row['tingkat'],
                        'rombel' => $row['rombel'],
                        'user_id' => $row['user_id'],
                        'nama_smp' => $row['nama_smp'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                } else {
                    $siswaId = $siswa->id;
                }

                // 2. Buat/update nilai
                DB::table('nilais')->updateOrInsert(
                    ['siswa_id' => $siswaId],
                    [
                        'nilai' => $row['nilai'],
                        'npsn_sma' => $this->npsn,
                        'npsn_smp' => $row['npsn_smp'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
            }

            DB::commit();
//            Log::info('Successfully processed batch', ['count' => count($this->data)]);

        } catch (\Exception $e) {
            DB::rollBack();
//            Log::error('Batch processing failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
