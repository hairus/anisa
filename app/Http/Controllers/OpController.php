<?php

namespace App\Http\Controllers;

use App\Http\Resources\siswaDapodik as ResourcesSiswaDapodik;
use App\Http\Resources\SiswaResource;
use App\Models\final_siswa;
use App\Models\sekolah_final;
use App\Models\siswa;
use App\Models\siswaDapodik;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OpController extends Controller
{
    public function getSiswa()
    {
        // $siswa = siswa::select('id')->where('npsn_sma', auth()->user()->username)->count();

        // $kelas_x = siswa::select('id', 'npsn_sma', 'tingkat')->where([
        //     'npsn_sma'=> auth()->user()->username,
        //     'tingkat'=> 10,
        // ])->count();


        // $kelas_xi = siswa::select('id', 'npsn_sma', 'tingkat')->where([
        //     'npsn_sma'=> auth()->user()->username,
        //     'tingkat'=> 11,
        // ])->count();

        // $kelas_xii = siswa::select('id', 'npsn_sma', 'tingkat')->where([
        //     'npsn_sma'=> auth()->user()->username,
        //     'tingkat'=> 12,
        // ])->count();

        // return response()->json([
        //     "siswa" => $siswa,
        //     "kelas10" => $kelas_x,
        //     "kelas11" => $kelas_xi,
        //     "kelas12" => $kelas_xii,
        // ]);
        $siswa = siswaDapodik::select('id')->where('npsn_sekolah_sekarang', auth()->user()->username)->count();

        $kelas_x = siswaDapodik::select('id', 'npsn_sekolah_sekarang', 'tingkat')->where([
            'npsn_sekolah_sekarang'=> auth()->user()->username,
            'tingkat'=> 10,
        ])->count();


        $kelas_xi = siswaDapodik::select('id', 'npsn_sekolah_sekarang', 'tingkat')->where([
            'npsn_sekolah_sekarang'=> auth()->user()->username,
            'tingkat'=> 11,
        ])->count();

        $kelas_xii = siswaDapodik::select('id', 'npsn_sekolah_sekarang', 'tingkat')->where([
            'npsn_sekolah_sekarang'=> auth()->user()->username,
            'tingkat'=> 12,
        ])->count();

        return response()->json([
            "siswa" => $siswa,
            "kelas10" => $kelas_x,
            "kelas11" => $kelas_xi,
            "kelas12" => $kelas_xii,
        ]);


    }

    public function getsiswas()
    {
        $paginate = request('params', 10);
        $sort_direction = request('sort_direction', 'asc');
        $sort_field = request('sort_field', 'tingkat');
        $search = request('search', '');

        $siswas = siswaDapodik::where('npsn_sekolah_sekarang', auth()->user()->username)
        ->Where('nama', 'like', '%'.$search.'%')
        ->orderBy($sort_field, $sort_direction)
        ->orderBy('rombel')
        ->orderBy('nama')
        ->paginate($paginate);

        return ResourcesSiswaDapodik::collection($siswas);

    }

    public function saveSiswasDapodik(Request $request)
    {
//        dd($request);
        /* ambil referensi dari siswa dapodik*/
        $user = siswaDapodik::where('npsn_sekolah_sekarang', auth()->user()->username)->first();

        $sim = siswaDapodik::create([
            "nama" => $request->form['name'],
            "sekolah_asal" => $user->sekolah_asal,
            "tingkat" => $request->form['tingkat'],
            "rombel" => $request->form['rombel'],
            "nisn" => $request->form['nisn'],
            "jenjang" => $user->jenjang,
            "status" => $user->status,
            "npsn_sekolah_sekarang" => $user->npsn_sekolah_sekarang,
            'peserta_didik_id' => uuid_create(),
        ]);

        return $sim;
    }

    public function delSiswaDapodik($id)
    {
        $siswas = siswaDapodik::find($id);
        $siswas->delete();

        return response()->json([], 202);
    }

    public function finalSiswa(Request $request)
    {
        //copy seluruh siswadapodik ke table siswa
        $siswas = siswaDapodik::where('npsn_sekolah_sekarang', auth()->user()->username)->get();

        $cek = siswa::where('user_id', auth()->user()->id)->count();
        if($cek > 0){
            $delete = siswa::where('user_id', auth()->user()->id)->delete();
        }
        foreach ($siswas as $data){

            $sim = siswa::create([
                "name" => $data->nama,
                "nisn" => $data->nisn,
                "npsn_sma" => auth()->user()->username,
                "npsn_smp" => 0,
                "tingkat" => $data->tingkat,
                "rombel" => $data->rombel,
                "user_id" => auth()->user()->id,
                "nama_smp" => $data->sekolah_smp ? $data->sekolah_smp : "-",
            ])->nilai()->create([
                'nilai' => 0,
                "npsn_smp" => 0,
                "npsn_sma" => auth()->user()->username,
            ]);
        }
     /** final siswa */
        $final = final_siswa::where('user_id', auth()->user()->id)->first();
        $final->update([
            "final" => true
        ]);

     return response()->json([], 200);
    }

//    public function final()
//    {
//        $final = sekolah_final::all();
//        foreach ($final as $data){
//            $sim = final_siswa::create([
//                'user_id' => $data->user_id ? $data->user_id : "0",
//                'kab_id' => $data->kab_id ? $data->kab_id : "0",
//                "npsn" => $data->npsn ? $data->npsn : "0",
//            ]);
//        }
//    }
}
