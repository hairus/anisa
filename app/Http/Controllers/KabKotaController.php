<?php

namespace App\Http\Controllers;

use App\Models\kab_kota;
use App\Models\sekolah_final;
use App\Models\siswa;
use App\Models\smas;
use App\Models\smps;
use Illuminate\Http\Request;

class KabKotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kabs = kab_kota::select('id')->count();
        $smas = smas::select('id')->count();
        $smps = smps::select('id')->count();
        $siswas = siswa::select('id')->count();

        return response()->json([
            'kabs' => $kabs,
            "smas" => $smas,
            "smps" => $smps,
            "siswas" => $siswas,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kabs = kab_kota::with(['smas' => function ($q) {
            $q->whereHas('siswas');
        }])->with(['smaNo' => function ($y) {
            $y->doesntHave('siswasNo');
        }])->with('finals', 'NoFinals')->where('id', '<>', 99)->get();

        return $kabs;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function belum($id)
    {
        $kab_kota = smas::doesntHave('siswas')->where('kab_id', $id)->get();

        return $kab_kota;
    }

    public  function  sudah($id)
    {
        $kab_kota = smas::whereHas('siswas')->where('kab_id', $id)->get();

        return $kab_kota;
    }

    public function final($id)
    {
        $kab_final = sekolah_final::where('kab_id', $id)->where('final', 1)->with('smas')->get();

        return $kab_final;
    }

    public function nofinal($id)
    {
        $kab_final = sekolah_final::where('kab_id', $id)->where('final', 0)->with('smas')->get();

        return $kab_final;
    }


    public  function getData($id)
    {
        ini_set('memory_limit', '256M');
        $kabs = smas::where('kab_id', $id)->withCount('siswas')->with('dapodik')->with('finals', 'kabs')->get();

        return $kabs;
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(kab_kota $kab_kota)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, kab_kota $kab_kota)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(kab_kota $kab_kota)
    {
        //
    }
}
