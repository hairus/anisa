<?php

namespace App\Http\Controllers;

use App\Http\Resources\SiswaResource;
use App\Models\siswa;
use App\Models\siswaDapodik;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paginate = request('paginate', 10);
        $search = request('q', '');
        $sort_direction = request('sort_direction', 'asc');
        $sort_field = request('sort_field', 'id');

        $siswa = siswa::with('smas', 'smps')
        ->where('npsn_sma', auth()->user()->username)
        ->search(trim($search))
        ->orderBy($sort_field, $sort_direction)
        ->paginate($paginate);

        $gg =  SiswaResource::collection($siswa);

        return $gg;
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

        $sim = siswaDapodik::create([
            'sekolah_asal' => auth()->user()->name,
            'nama' => $request->nama,
            'jenjang' => $request->jenjang,
            'status' => 'Negeri',
            'nisn' => $request->nisn,
            'npsn_sekolah_sekarang' => auth()->user()->username,
            'tingkat' => $request->tingkat,
            'sekolah_smp' => $request->selectedSmp['nama_smp'],
            'peserta_didik_id' =>uuid_create(),
            'rombel' => strtoupper($request->nmKelas)
        ]);

        return response()->json($sim, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(siswa $siswa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(siswa $siswa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, siswa $siswa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(siswa $siswa)
    {
        //
    }
}
