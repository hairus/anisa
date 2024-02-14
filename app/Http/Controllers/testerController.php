<?php

namespace App\Http\Controllers;

use App\Imports\SiswaImport;
use App\Models\pesan;
use App\Models\siswa;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Maatwebsite\Excel\Facades\Excel;

use function PHPUnit\Framework\isEmpty;

class testerController extends Controller
{
    use DispatchesJobs;

    public function uploadgg()
    {
        return view('upload');
    }

    public function store(Request $request)
    {
        // dd(auth()->user()->id);
        $validation = $request->validate([
            "file" => "required"
        ]);
        $file = $request->file('file');

        $fileName = time() . '.' . $file->getClientOriginalExtension();

        $path = $request->file->move(public_path('excel'), $fileName);

        $user_id = auth()->user()->id;
        $imports = new SiswaImport($user_id);
        $imports->import($path);
        // try {

        // } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
        //     $failures = $e->failures();

        //     foreach ($failures as $failure) {
        //         $failure->row(); // row that went wrong
        //         $failure->attribute(); // either heading key (if using heading row concern) or column index
        //         $failure->errors(); // Actual error messages from Laravel validator
        //         $failure->values(); // The values of the row that has failed.
        //         var_dump($failure);
        //     }
        // }


        return back()->withStatus("excel success");
    }
}
