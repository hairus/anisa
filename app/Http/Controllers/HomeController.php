<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use function PHPSTORM_META\map;

class HomeController extends Controller
{
    public function index()
    {
        return view('App');
    }

    public function praktek()
    {
        dd(auth()->user());
        $gabung = [];
        $array = array(
            "0" => "name",
            "1" => "nisn",
            "2" => "npsn_sma",
            "3" => "npsn_smp",
            "4" => "tingkat",
            "5" => "rombel",
            "6" => "nilai",
        );
        $gabung = collect($array);
        echo $gabung;
    }

    public function lgt()
    {
        auth()->logout();

        return "OK";
    }
}
