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
        $topup = Billing::where('user_id', Auth::user()->id)
                    ->where('type', 'topup')
                    ->where('status', 'paid')
                    ->sum('amount');
        $beli = Billing::where('user_id', Auth::user()->id)
                    ->where('type', 'beli')
                    ->where('status', 'paid')
                    ->sum('amount');
        $balance = $topup - $beli;
        
        return view('billing.index', ['balance' => $balance]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable()
    {
        $billings = Billing::where('user_id', Auth::user()->id)
                        ->where('type', 'topup')
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        foreach($billings as $billing){
            $billing['amount'] = 'Rp. ' . number_format($billing['amount'], 0, ',', '.');
            $billing['date'] = date_format($billing->created_at, 'd-m-Y');
            $billing['status'] = $billing['status'];
            $billing['id'] = $billing['id'];
            if($billing['payment_method'] == 'bank_transfer'){
                $billing['payment_method'] = 'Bank Transfer';
            } else if($billing['payment_method'] == 'credit_card'){
                $billing['payment_method'] = 'Kartu Kredit';
            }else if($billing['payment_method'] == 'cstore'){
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
            'status' => 'pending',
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

        // Billing::where('uuid', $request->uuid)->first();

        $billing = Billing::where('uuid', $request->uuid)->first();
        return response()->json([
            'success' => true,
            'billing' => $billing
        ]);  

        // // Set your Merchant Server Key
        // \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        // // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        // \Midtrans\Config::$isProduction = env('MIDTRANS_PRODUCTION');
        // // Set sanitization on (default)
        // \Midtrans\Config::$isSanitized = true;
        // // Set 3DS transaction for credit card to true
        // \Midtrans\Config::$is3ds = true;
        
        // $params = array(
        //     'transaction_details' => array(
        //         'order_id' => $request->uuid,
        //         'gross_amount' => $billing->amount,
        //     ),
        //     'customer_details' => array(
        //         'first_name' => Auth::user()->name,
        //         'email' => Auth::user()->email,
        //         // 'phone' => '08111222333',
        //     ),
        // );
        
        // $snapToken = \Midtrans\Snap::getSnapToken($params);

        // return response()->json([
        //     'success' => true,
        //     'token' => $snapToken
        // ]);  
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function transaction()
    {        
        return view('billing.transaction');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable_transaction()
    {
        $billings = Billing::where('user_id', Auth::user()->id)
                        ->where('status', 'paid')
                        ->orderBy('created_at', 'desc')
                        ->get();

        foreach($billings as $billing){
            $billing['amount'] = 'Rp. ' . number_format($billing['amount'], 0, ',', '.');
            $billing['date'] = date_format($billing->created_at, 'd-m-Y');
            $billing['id'] = $billing['id'];
            if($billing['payment_method'] == 'bank_transfer'){
                $billing['payment_method'] = 'Bank Transfer';
            } else if($billing['payment_method'] == 'credit_card'){
                $billing['payment_method'] = 'Kartu Kredit';
            }else if($billing['payment_method'] == 'cstore'){
                $billing['payment_method'] = 'Minimarket';
            } else if($billing['payment_method'] == 'bri_epay'){
                $billing['payment_method'] = 'BRImo';
            } else if($billing['payment_method'] == 'qris'){
                $billing['payment_method'] = 'QRIS';
            }

            if($billing['type'] == 'beli'){
                $billing['description'] = 'Pembelian paket ' . ucfirst($billing['package']);
            } else {
                $billing['description'] = 'Topup saldo melalui ' . ucfirst($billing['payment_method']);
            }
            
        }
        return DataTables::of($billings)->make(true);
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

    public function ipaymu(Request $request)
    {
        $billing = Billing::where('uuid', $request->uuid)->first();

        // dd($billing->package);

        // SAMPLE HIT API iPaymu v2 PHP // 
        $va           = '1179005216876545'; //get on iPaymu dashboard
        $apiKey       = 'tHzgMnwWmMvxG6rPyt1Y76OgwxJv2.'; //get on iPaymu dashboard

        // $url          = 'https://sandbox.ipaymu.com/api/v2/payment'; // for development mode

        $url          = 'https://my.ipaymu.com/api/v2/payment'; // for production mode
        
        $method       = 'POST'; //method
        
        $package = 'Paket ' . ucfirst($billing->package);
        
        //Request Body//
        $body['product']    = array($package);
        $body['qty']        = array('1');
        $body['price']      = array($billing->amount);
        $body['returnUrl']  = 'https://your-website.com/thank-you-page';
        $body['cancelUrl']  = 'https://your-website.com/cancel-page';
        $body['notifyUrl']  = 'https://your-website.com/callback-url';
        $body['referenceId'] = '1234'; //your reference id
        //End Request Body//

        //Generate Signature
        // *Don't change this
        $jsonBody     = json_encode($body, JSON_UNESCAPED_SLASHES);
        $requestBody  = strtolower(hash('sha256', $jsonBody));
        $stringToSign = strtoupper($method) . ':' . $va . ':' . $requestBody . ':' . $apiKey;
        $signature    = hash_hmac('sha256', $stringToSign, $apiKey);
        $timestamp    = Date('YmdHis');
        //End Generate Signature


        $ch = curl_init($url);

        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'va: ' . $va,
            'signature: ' . $signature,
            'timestamp: ' . $timestamp
        );

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_POST, count($body));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $err = curl_error($ch);
        $ret = curl_exec($ch);
        curl_close($ch);

        if($err) {
            echo $err;
        } else {

            //Response
            $ret = json_decode($ret);
            if($ret->Status == 200) {
                $sessionId  = $ret->Data->SessionID;
                $url        =  $ret->Data->Url;
                // dd($url);
                return response()->json([
                    'success' => true,
                    'url' => $url
                ]); 
            }
        }
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
