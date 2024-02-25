<?php

namespace App\Http\Controllers;

use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;

class PosangController extends Controller
{
    public function tambah()
    {
        // dd(auth()->user()->id);
        $jobs = [];
        $batch = null;

        // $import = new SiswaImport(1);

        // $batch = Bus::batch($jobs)->dispatch();

        // return redirect('/posang?batch_id='.$batch->id);
    }

    public function posang(Request $request)
    {
        $batch = null;
        if($request->batch_id){
            $batch = Bus::findBatch($request->batch_id);
        }

        dd($batch->progress());
    }

    public function listen()
    {
        Artisan::call('queue:work', ['--tries=1']);

        return back();
    }
}
