<?php

namespace App\Http\Controllers;

use App\Models\kab_kota;
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
        //
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
    public function show(kab_kota $kab_kota)
    {
        //
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
