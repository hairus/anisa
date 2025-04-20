<?php

namespace App\Http\Controllers;

use App\Models\smps;
use Illuminate\Http\Request;

class SmpsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if($_GET['params'] && $_GET['search'] == ""){
            $smp = smps::with('kabs')->paginate($_GET['params']);

            return $smp;
        }else if($_GET['search'] && $_GET['params']){
            $smp = smps::with('kabs')
                ->where('npsn_smp', 'LIKE', '%'.$_GET['search'].'%')
                ->orWhere("nama_smp", 'LIKE', '%'.$_GET['search'].'%')
                ->paginate($_GET['params']);

            return $smp;
        }else{
            $smp = smps::with('kabs')->paginate(5);

            return $smp;
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
        $sim = smps::create([
            "kode_un" => $request->form["kodeUn"],
            "npsn_smp" => $request->form["npsn"],
            "nama_smp" => $request->form["smp"],
            "kab_id" => $request->form["kab_id"],
            "jenjang" => $request->form["jenjang"]

        ]);

        return $sim;
    }

    /**
     * Display the specified resource.
     */
    public function show(smps $smps)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(smps $smps)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, smps $smps)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $del = smps::find($id);
        $del->delete();

        return "success del smp";
    }
}
