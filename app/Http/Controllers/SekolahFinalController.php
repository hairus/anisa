<?php

namespace App\Http\Controllers;

use App\Models\sekolah_final;
use App\Models\siswa;
use App\Models\smas;
use App\Models\User;
use Illuminate\Http\Request;

class SekolahFinalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

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
        $user = auth()->user();
        $final = sekolah_final::where('user_id', auth()->user()->id)->update([
            "final" => true
        ]);
        return $final;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);
        $siswas = siswa::where('user_id', $id)->delete();
        $user->finalSiswa()->update([
            "final" => false
        ]);
        $user->antrian()->update([
            "selesai" => false
        ]);

        return response()->json('sukses update data', 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, sekolah_final $sekolah_final)
    {

        $sekolah = smas::find($request->id);
        $user = User::where('username', $sekolah->npsn)->first();
        $sekolah = sekolah_final::where('user_id', $user->id)->first();
        $sekolah->final = 0;
        $sekolah->save();
        return $sekolah;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(sekolah_final $sekolah_final)
    {
        //
    }
}
