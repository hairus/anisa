<?php

namespace App\Http\Controllers;

use App\Imports\SiswaImport;
use App\Models\smas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
//        $smas = smas::find(717);
//        $length = 8;
//        $randomletter = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
//        $create = User::create([
//            "name" => $smas->nm_sekolah,
//            "password" => bcrypt($randomletter),
//            "email" => 'sekolah1@gmail.com',
//            "username" => $smas->npsn,
//            "password_asli" => $randomletter
//        ]);
        $length = 8;
        $randomletter = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
        $update = User::find(726);
        $update->password = Hash::make($randomletter);
        $update->password_asli = $randomletter;
        $update->save();
        dd($update);

//        set_time_limit(0);
//        $user = User::all();
//        foreach ($user as $data) {
//            $length = 8;
//            $randomletter = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
//            $user = User::find($data->id);
//            $user->password_asli = $randomletter;
//            $user->password = bcrypt($randomletter);
//            $user->save();
//        }


    }

    public function listen()
    {
//        Artisan::call('queue:work --daemon');
    Log::info("tulis");

        return "gg";
    }
}
