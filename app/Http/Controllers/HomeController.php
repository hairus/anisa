<?php

namespace App\Http\Controllers;

use App\Models\BatchUser;
use App\Models\sekolah_final;
use App\Models\smas;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use function PHPSTORM_META\map;

class HomeController extends Controller
{
    public function index()
    {
        return view('app');
    }

    public function praktek()
    {
        $final = sekolah_final::where('user_id', '<>', 1)->get();
        foreach($final as $data){
            $user = User::where('id', $data->user_id)->first();
            $sma = smas::where('npsn', $user->username)->first();
            $update = sekolah_final::where('user_id', $user->id)->first();
            $update->npsn = $user->username;
            $update->kab_id = $sma->kab_id;
            $update->save();
        }

        return "sukses";

    }

    public function lgt()
    {
        auth()->logout();

        return "OK";
    }

    public function cobaJob()
    {
        $batch = BatchUser::where('user_id', 408)->orderby('created_at', 'DESC')->first();
        $b = strtotime($batch->created_at);
        $query = DB::select('SELECT * FROM job_batches where finished_at is null and created_at <= '.$b);
        $count = count($query);
        dd($count);
    }
}
