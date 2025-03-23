<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResiduController extends Controller
{
    public function index()
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
}
