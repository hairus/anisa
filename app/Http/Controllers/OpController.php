<?php

namespace App\Http\Controllers;

use App\Http\Resources\SiswaResource;
use App\Models\siswa;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OpController extends Controller
{
    public function getSiswa()
    {
        $siswa = siswa::where('npsn_sma', auth()->user()->username)->get();

        return SiswaResource::collection($siswa);

    }
}
