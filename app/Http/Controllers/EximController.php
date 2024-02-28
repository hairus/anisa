<?php

namespace App\Http\Controllers;

use App\Exports\SiswaExport;
use App\Exports\SmpsExport;
use App\Exports\UploadsExport;
use App\Imports\UploadsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Maatwebsite\Excel\Facades\Excel;

class EximController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = auth()->user()->id;

        return Excel::download(new SiswaExport($user_id), 'users.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Excel::download(new SmpsExport, 'smp.xlsx');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Excel::import(new UploadsImport(), 'users.xlsx');
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
        $cek = Bus::findBatch($id);

        return $cek;
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
