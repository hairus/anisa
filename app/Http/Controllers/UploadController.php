<?php

namespace App\Http\Controllers;

use App\Imports\UploadsImport;
use App\Jobs\UploadJobs;
use App\Models\upload;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Maatwebsite\Excel\Facades\Excel;

class UploadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $upload = upload::where('user_id', auth()->user()->id)->paginate(5);

        return $upload;
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
        // include "./excel.php";
        $validation = $request->validate([
            "file" => "required"
        ]);
        $file = $request->file('file');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $path = $request->file->move(public_path('excel'), $fileName);
        // $data = new Spreadsheet_Excel_Reader($path,false);
        // dd($data);

        $user_id = auth()->user()->id;

        $cek = upload::where('user_id', $user_id)->first();

        if ($cek) {
            upload::where('user_id', $user_id)->delete();
        }
        $batch = Bus::batch([
            new UploadJobs($user_id, $fileName)
        ])->dispatch();

        // $import = new UploadsImport($user_id);
        // Excel::import($import, $path);

        // dd('Row count: ' . $import->getRowCount());
        // $count = 0;
        // $jumlah = [];
        // $spreadsheet = Excel::toArray(new UploadsImport($user_id), $path);
        // foreach ($spreadsheet as $p) {
        //     // return $p[0];
        //     foreach ($p as $i) {
        //         $count++;
        //         $data = count($p);
        //         return $i;
        //         // if($i[])
        //         // $date_file = $i[2];
        //         // if ($today_carbon >= $date_file) {
        //         //     Excel::import(new UserImport, $file);
        //         //     return redirect('form')->with('success', 'ok');
        //         // } else {
        //         //     return redirect('form')->with('erro', 'error');
        //         // }
        //         // if ($columns == 3) {
        //         //     Excel::import(new UserImport, $file);
        //         //     return redirect('form')->with('success', 'ok');
        //         // } else {
        //         //     return redirect('form')->with('erro', 'error');
        //         // }
        //     }
        // }

        // $batch = Bus::batch([
        //     new UploadJobs($user_id, $fileName)
        //     // Excel::import(new UploadsImport($user_id), $path)
        // ])->dispatch();




        // Excel::import(new UploadsImport($user_id), $fileName);
        // unlink($path);

        return $batch->id;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
