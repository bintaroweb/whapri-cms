<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Device;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid as Generator;

class DeviceController extends Controller
{
    private function price($package){
        if($package == 'trial'){
            $price = 0;
        } else if($package == 'silver'){
            $price = 50000;
        } else if($package == 'gold'){
            $price = 100000;
        } else {
            $price = 150000;
        }

        return $price;
    }

    private function balance(){
        $topup = Billing::where('user_id', Auth::user()->id)
                    ->where('type', 'topup')
                    ->where('status', 'paid')
                    ->sum('amount');
        $beli = Billing::where('user_id', Auth::user()->id)
                    ->where('type', 'beli')
                    ->where('status', 'paid')
                    ->sum('amount');
        $balance = $topup - $beli;
        return $balance;
    }

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
        $trial = Device::where('user_id', Auth::user()->id)
                    ->where('package', 'trial')
                    ->count();
        $balance = $this->balance();

        return view('device.create', ['trial' => $trial, 'balance' => $balance]);
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
            'note'  => 'nullable|string',
            'package' => 'required|string'       
        ]);

        if($this->price($request->package) > $this->balance() ){
            return redirect('/devices')->with('error', 'Perangkat gagal ditambah');
        }

        if($request != 'trial'){
            $device = Device::create([
                'user_id' => Auth::user()->id,
                'name' => $request['name'],
                'note' => $request['note'],
                'status' => 'disconnected',
                'package' => $request['package'],
                'active_period' => date('Y-m-d', strtotime("+30 days"))
            ]);

            Billing::create([
                'amount' => $this->price($request->package),
                'status' => 'paid',
                'type' => 'beli',
                'package' => $request['package'],
                'user_id' => Auth::user()->id,
                'device_id' => $device->id
            ]);
        } else {
            $device = Device::create([
                'uuid' => $request['uuid'],
                'user_id' => Auth::user()->id,
                'name' => $request['name'],
                'note' => $request['note'],
                'status' => $request['status'],
                'package' => $request['package'],
                'active_period' => date('Y-m-d', strtotime("+7 days"))
            ]);

            Billing::create([
                'amount' => $this->price($request->package),
                'status' => 'paid',
                'type' => 'beli',
                'package' => $request['package'],
                'user_id' => Auth::user()->id,
                'device_id' => $device->id
            ]);
        }

        // return response()->json([
        //     'success' => true,
        //     'template' => $device
        // ]);  
        
        return redirect('/devices')->with('success', 'Perangkat berhasil ditambah');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $device = Device::where('uuid', $uuid)->first();

        return response()->json([
            'success' => true,
            'device' => $device
        ]); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function status(Request $request){
        $request->validate([
            'uuid' => 'required|string',
            'status' => 'required|string'
        ]);

        Device::where('uuid', $request->uuid)
            ->update([
                'status' => $request->status
            ]);

        return response()->json([
            'success' => true,
            'status' => $request->status
        ]); 
        
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
