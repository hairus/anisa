<?php

namespace App\Http\Controllers;

use App\Http\Resources\SiswaResource;
use App\Models\siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OpController extends Controller
{
    public function getSiswa()
    {
        $siswa = siswa::select('id')->where('npsn_sma', auth()->user()->username)->count();

        $kelas_x = siswa::select('id', 'npsn_sma', 'tingkat')->where([
            'npsn_sma'=> auth()->user()->username,
            'tingkat'=> 10,
        ])->count();


        $kelas_xi = siswa::select('id', 'npsn_sma', 'tingkat')->where([
            'npsn_sma'=> auth()->user()->username,
            'tingkat'=> 11,
        ])->count();

        $kelas_xii = siswa::select('id', 'npsn_sma', 'tingkat')->where([
            'npsn_sma'=> auth()->user()->username,
            'tingkat'=> 12,
        ])->count();

        return response()->json([
            "siswa" => $siswa,
            "kelas10" => $kelas_x,
            "kelas11" => $kelas_xi,
            "kelas12" => $kelas_xii,
        ]);

    }
}
