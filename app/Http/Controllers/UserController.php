<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\Role;
use App\Models\User;
use App\Models\UserOutlet;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a middleware auth.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('additional')->only('edit','update', 'destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return view('user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 
        // $outlets = Outlet::where('user_id', Auth::user()->parent_id);
        // $roles = Role::where('user_id', Auth::user()->parent_id);
        // return view('user.create', ['outlets' => $outlets, 'roles' => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email'  => 'required|string',
            'password' => 'required|string',       
        ]);

        User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        return redirect('/users')->with('success', 'Karyawan berhasil ditambah');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $uuid
     * @return \Illuminate\Http\Response
     */
    public function edit($uuid)
    {
        $user = User::where('uuid', $uuid)->first();
        return view('profile.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uuid)
    {
        $request->validate([
            'name' => 'required|string',
            'password' => 'nullable|string'           
        ]);

        if(!empty($request->password)){
            User::where('uuid', $uuid)
                ->update([
                    'name' => $request->name, 
                    'password' => Hash::make($request->password)
                ]);
        } else {
            User::where('uuid', $uuid)
                ->update([
                    'name' => $request->name, 
                ]);
        }

        return redirect('/users/'.$uuid.'/edit')->with('success', 'Profile berhasil diubah');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        User::where('uuid', $uuid)->delete();
        return redirect('/users')->with('success', 'Karyawan berhasil dihapus');
    }
}
