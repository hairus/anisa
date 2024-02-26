<?php

namespace App\Http\Controllers;

use App\Models\siswa;
use Illuminate\Http\Request;

class OpController extends Controller
{
    public function getSiswa()
    {
        $siswas = siswa::where('npsn_sma', auth()->user()->username)->count();

        return $siswas;
    }
}
