<?php

namespace App\Http\Controllers;

use App\Models\sekolah_final;
use App\Models\siswa;
use App\Models\siswaDapodik;
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
        $user = auth()->user();
        siswa::where([
            'user_id' => $user->id
        ])->delete();

        $salin = siswaDapodik::where('npsn_sekolah_sekarang', $user->username)
            ->orderBy('tingkat')
            ->orderBy('rombel')
            ->orderBy('nama')
            ->get();
        foreach ($salin as $s) {
            $sim = siswa::create([
                'name' => $s->nama,
                'nisn' => $s->nisn,
                'npsn_sma' => $user->username,
                'tingkat' => $s->tingkat,
                'rombel' => $s->rombel,
                'nama_smp' => $s->sekolah_smp ? $s->sekolah_smp : '-',
                'user_id' => $user->id
            ]);
        }

        return response()->json([], 201);
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
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        return $request;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(sekolah_final $sekolah_final)
    {
        //
    }
}
