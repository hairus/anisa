<?php

namespace App\Http\Controllers;

use App\Models\role_user;
use App\Models\smas;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if ($_GET['params'] && $_GET['search'] == "") {
            $users = User::with(['role' => function ($q) {
                $q->with('roles');
            }])->paginate($_GET['params']);
            return $users;
        } else if ($_GET['search'] && $_GET['params']) {
            $users = User::with(['role' => function ($q) {
                $q->with('roles');
            }])->where('name', 'LIKE', '%' . $_GET['search'] . '%')
                ->paginate($_GET['params']);
            return $users;
        } else {
            $users = User::with(['role' => function ($q) {
                $q->with('roles');
            }])->paginate(2);

            return $users;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sma = smas::all();
        foreach ($sma as $data) {
            $sim = User::create([
                "name" => $data->nm_sekolah,
                "email" => $data->npsn . '@gmail.com',
                "password" => bcrypt($data->npsn),
                "username" => $data->npsn
            ]);
            $role_user = role_user::create([
                "user_id" => $sim->id,
                "role_id" => 2
            ]);
        }
        return "success create user";
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $up = User::where('id', auth()->user()->id)->first();
        $up->update([
            'password' => bcrypt($request->password),
            'password_asli' => $request->password
        ]);
        return $up;


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
