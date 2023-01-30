<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid as Generator;

class DeviceController extends Controller
{
    /**
     * Create a middleware auth.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('device.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable()
    {
        $devices = Device::where('user_id', Auth::user()->id)->get();
        return DataTables::of($devices)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $uuid = Generator::uuid4()->toString();
        return view('device.create', ['uuid' => $uuid]);
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
            'note'  => 'string'        
        ]);

        Device::create([
            'uuid' => $request['uuid'],
            'user_id' => Auth::user()->id,
            'name' => $request['name'],
            'note' => $request['note'],
            'status' => $request['status'],
            'active_period' => date('Y-m-d', strtotime("+30 days"))
        ]);

        // return redirect('/devices')->with('success', 'Teknisi berhasil ditambah');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($uuid)
    {
        $data = Device::where('uuid', $uuid)->first();
        return view('device.edit', ['device' => $data]);
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
            'contact'  => 'required|string'        
        ]);

        Device::where('uuid', $uuid)
                ->update([
                    'name' => $request->name, 
                    'contact' => $request->contact
                ]);
        
         return redirect('/device')->with('success', 'Perangkat berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        Device::where('uuid', $uuid)->delete();
        return redirect('/device')->with('success', 'Teknisi berhasil dihapus');
    }
}
