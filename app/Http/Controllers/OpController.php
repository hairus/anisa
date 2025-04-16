<?php

namespace App\Http\Controllers;

use App\Exports\masterSiswa;
use App\Exports\siswaDapodikExport;
use App\Http\Resources\siswaDapodik as ResourcesSiswaDapodik;
use App\Http\Resources\SiswaResource;
use App\Jobs\InsertDataJob;
use App\Models\AntrianSiswa;
use App\Models\BatchUser;
use App\Models\final_siswa;
use App\Models\nilai;
use App\Models\sekolah_final;
use App\Models\siswa;
use App\Models\siswaDapodik;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
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

        $siswa_nilai =  siswa::select('npsn_sma', 'id')->with('nilai')->where([
            'npsn_sma' => auth()->user()->username,
        ])->count();

        $nilai_x = siswa::select('npsn_sma', 'id')->with('nilai')->where([
            'npsn_sma' => auth()->user()->username,
            'tingkat' => 10,
        ])->count();
        $nilai_xi = siswa::select('npsn_sma', 'id')->with('nilai')->where([
            'npsn_sma' => auth()->user()->username,
            'tingkat' => 11
        ])->count();
        $nilai_xii = siswa::select('npsn_sma', 'id')->with('nilai')->where([
            'npsn_sma' => auth()->user()->username,
            'tingkat' => 12
        ])->count();


        return response()->json([
            "siswa" => $siswa,
            "kelas10" => $kelas_x,
            "kelas11" => $kelas_xi,
            "kelas12" => $kelas_xii,
            'nilai10' => $nilai_x,
            'nilai11' => $nilai_xi,
            'nilai12' => $nilai_xii,
            'siswa_nilai' => $siswa_nilai,
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

    public function getAntrian()
    {
        $baru = DB::select('SELECT
            a.created_at
        FROM
            job_batches a
            JOIN batch_users b ON a.id = b.batch_id
            JOIN users c ON c.id = b.user_id
            WHERE a.cancelled_at is null AND a.finished_at is NULL AND c.id ='. auth()->user()->id.' LIMIT 1');
        $query = DB::select('SELECT * FROM job_batches where finished_at is null and created_at < '.$baru[0]->created_at);
        $count = count($query);

        return response()->json($count, 200);
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

        $user = auth()->user();
        $jobStatus = AntrianSiswa::where('user_id', $user->id)->first();
        if ($jobStatus && $jobStatus->selesai) {
            return response()->json(['message' => 'Job is already processing for this user. Please wait.'], 429);
        }

        final_siswa::updateOrCreate(
            ['user_id' => $user->id],
            ['final' => true]
        );

        AntrianSiswa::updateOrCreate(
            ['user_id' => $user->id],
            ['selesai' => true]
        );

//        InsertDataJob::dispatch($user->id, $user->username);

        $finalSiswa = $user->finalSIswa->final;
        // Response sukses
        return response()->json($finalSiswa, 200);
    }

    public function updateSiswasDapodik(Request $request, $id)
    {
        $udpate = siswaDapodik::find($id);
        $udpate->update([
            'nama' => $request->form['name'],
            'tingkat' => $request->form['tingkat'],
            'rombel' => $request->form['rombel'],
            'nisn' => $request->form['nisn'],
        ]);
    }

    public function downloadExcel()
    {
        $user = auth()->user();

        return Excel::download(new masterSiswa($user->username), 'master_data.xlsx');
    }

    public function cekNilai()
    {
        $siswa = nilai::where('npsn_sma', auth()->user()->username)->first();

        return response()->json($siswa);
    }
}
