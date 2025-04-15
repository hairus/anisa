<?php

namespace App\Http\Controllers;

use App\Models\nilai;
use App\Models\siswa;
use App\Models\siswaDapodik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResiduController extends Controller
{
    public function index1()
    {
        $username = auth()->user()->username;
        $query = DB::select('SELECT a.nisn,
                     a.name,
                     a.npsn_smp,
                     a.nama_smp,
                     a.tingkat,
                     a.rombel,
                     a.npsn_smp,
                     a.nama_smp,
                     c.nilai
                FROM siswas a
                   INNER JOIN (SELECT nisn
                               FROM   siswas
                               GROUP  BY nisn,npsn_sma
                               HAVING COUNT(nisn) > 1) b
                           ON a.nisn = b.nisn
                        LEFT JOIN nilais c ON c.siswa_id = a.id
                where a.npsn_sma = '.$username.'
                ORDER BY a.nisn');
        $count = count($query);
        return response()->json([
           "siswas" => $query,
           "count" => $count
        ]);

    }

    public function index()
    {
        $username = auth()->user()->username;
        $query = DB::select('SELECT a.nisn,
                    a.nama,
                    a.jenjang,
                    a.sekolah_smp,
                    a.rombel,
                    a.tingkat,
                    a.npsn_sekolah_sekarang
                FROM siswa_dapodiks a
                   INNER JOIN (SELECT nisn
                               FROM   siswa_dapodiks
                               GROUP  BY nisn ,npsn_sekolah_sekarang
                               HAVING COUNT(nisn) > 1) b
                           ON a.nisn = b.nisn
                where a.npsn_sekolah_sekarang = '.$username.'
                ORDER BY a.nisn');
        $count = count($query);
        return response()->json([
            "siswas" => $query,
            "count" => $count
        ]);
    }

    public function show()
    {
        $username = auth()->user()->username;
        $nilais = nilai::where([
            "npsn_sma" => $username
        ])->count();

        $nilai0 = nilai::where([
            "npsn_sma" => $username,
            "nilai" => 0
        ])->count();

        $siswasDapodik = siswaDapodik::select('id', 'npsn_sekolah_sekarang', 'tingkat')->where([
            'npsn_sekolah_sekarang'=> auth()->user()->username,
        ])->count();

        return response()->json([
            "nilais" => $nilais,
            "nilai0" => $nilai0,
            "siswasDapodik" => $siswasDapodik
        ], 200);
    }
}
