<?php

namespace App\Http\Controllers;

use App\Models\sekolah_final;
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
    public function show(sekolah_final $sekolah_final)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(sekolah_final $sekolah_final)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, sekolah_final $sekolah_final)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(sekolah_final $sekolah_final)
    {
        //
    }
}
