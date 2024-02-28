<?php

namespace App\Http\Controllers;

use App\Imports\SiswaImport;
use App\Models\User;
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
        if ($request->batch_id) {
            $batch = Bus::findBatch($request->batch_id);
        }

        dd($batch->progress());
    }

    public function generatePassword()
    {

        set_time_limit(0);
        $user = User::all();
        foreach ($user as $data) {
            $length = 8;
            $randomletter = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
            $user = User::find($data->id);
            $user->password_asli = $randomletter;
            $user->password = bcrypt($randomletter);
            $user->save();
        }


    }

    public function listen()
    {
        Artisan::call('queue:work', ['--tries=1']);

        return back();
    }
}
