<?php

namespace App\Http\Controllers;

use App\Models\role_user;
use App\Models\User;
use Illuminate\Http\Request;

class RoleUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $role_users = User::with(['role' =>function($q){
            $q->with('roles');
        }])->paginate(2);

        return $role_users;
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
        $role = role_user::create([
            "user_id" => $request->user_id,
            "role_id" => $request->role_id
        ]);

        return $role;
    }

    /**
     * Display the specified resource.
     */
    public function show($name)
    {
        $users = User::with(['role' =>function($q){
            $q->with('roles');
        }])->where('name','LIKE', "%".$name.'%')->paginate(2);


        return $users;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(role_user $role_user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, role_user $role_user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $del = role_user::find($id);
        $del->delete();

        return "hapus berhasil";
    }
}
