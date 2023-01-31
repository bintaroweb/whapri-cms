<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DataTables;

class BillingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        // $balance = DB::table('billing')
        //             ->where('user_id', Auth::user()->id)
        //             ->first();
        
        return view('billing.index', ['balance' => 0]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable()
    {
        $billings = Billing::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        // $data = [];
        foreach($billings as $billing){
            $billing['amount'] = 'Rp. ' . number_format($billing['amount'], 0, ',', '.');
            $billing['date'] = date_format($billing->created_at, 'd-m-Y');
            $billing['status'] = $billing['status'];
            $billing['id'] = $billing['id'];
            if($billing['payment_method'] == 'bank_transfer'){
                $billing['payment_method'] = 'Bank Transfer';
            } else if($billing['payment_method'] == 'cstore'){
                $billing['payment_method'] = 'Minimarket';
            } else if($billing['payment_method'] == 'bri_epay'){
                $billing['payment_method'] = 'BRImo';
            } else if($billing['payment_method'] == 'qris'){
                $billing['payment_method'] = 'QRIS';
            }
        }
        return DataTables::of($billings)->make(true);
    }

    public function store(Request $request) 
    {
        $request->validate([
            'amount' => 'required|string'       
        ]);

        Billing::create([
            'amount' => $request['amount'],
            'status' => 'Pending',
            'type' => 'topup',
            'user_id' => Auth::user()->id,
        ]);

        return response()->json([
            'success' => true
        ]); 

    }

    public function payment(Request $request) 
    {
        $request->validate([
            'uuid'   => 'required|string'     
        ]);

        $billing = Billing::where('uuid', $request->uuid)->first();

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = 'SB-Mid-server-9Da1lJip_CDlm-9h1qbSox3S';
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;
        
        $params = array(
            'transaction_details' => array(
                'order_id' => $request->uuid,
                'gross_amount' => $billing->amount,
            ),
            'customer_details' => array(
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => '08111222333',
            ),
        );
        
        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return response()->json([
            'success' => true,
            'token' => $snapToken
        ]);  
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail(Request $request)
    {
        $billing = Billing::where('uuid', $request->uuid)->where('user_id', Auth::user()->id)->first();
        $billing['amount'] = 'Rp. ' . number_format($billing['amount'], 0, ',', '.');
        if($billing['payment_method'] == 'bank_transfer'){
            $billing['payment_method'] = 'Bank Transfer';
        } else if($billing['payment_method'] == 'cstore'){
            $billing['payment_method'] = 'Minimarket';
        } else if($billing['payment_method'] == 'bri_epay'){
            $billing['payment_method'] = 'BRImo';
        } else if($billing['payment_method'] == 'qris'){
            $billing['payment_method'] = 'QRIS';
        }

        // if($request->ajax()){
            return response()->json([
                'success' => true,
                'billing' => $billing
            ]);
        // }  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     //
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        Billing::where('uuid', $uuid)->where('user_id', Auth::user()->id)->delete();
        return redirect('/billings')->with('success', 'Billing berhasil dihapus');
    }
}
