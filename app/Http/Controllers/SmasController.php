<?php

namespace App\Http\Controllers;

use App\Models\smas;
use Illuminate\Http\Request;

class SmasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if($_GET['params'] && $_GET['search'] == ""){
            $sma = smas::with('kabs')->with(['siswas' => function($q){
                $q->select('npsn_sma');
            }])->paginate($_GET['params']);

            return response()->json($sma, 200);
        }else if($_GET['search'] && $_GET['params']){

            $sma = smas::with('kabs')->with(['siswas' => function($q){
                $q->select('npsn_sma');
                }])
                ->where('npsn', 'LIKE', '%'.$_GET['search'].'%')
                ->orWhere("nm_sekolah", 'LIKE', '%'.$_GET['search'].'%')
                ->paginate(10);

            return response()->json($sma, 200);
        }else{
            $sma = smas::with('kabs')->with(['siswas' => function($q){
                $q->select('npsn_sma');
                }])->paginate(5);

            return response()->json($sma, 200);
        }

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
        $request->validate($request, [
            "kode_un" => "required|unique:smps,kode_un",
            "kb_id" => "required",
            "npsn_smp" => "required|unique:smps,npsn_smp"
        ]);
        $sim = smas::create([
            "kode_un" => $request->kode_un,
            "npsn_smp" => $request->npsn_smp,
            "nama_smp" => $request->nama_smp,
            "kab_id" => $request->kab_id,

        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(smas $smas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(smas $smas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, smas $smas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $sma = smas::find($id);
        $sma->delete();

        return "success delete";
    }
}
