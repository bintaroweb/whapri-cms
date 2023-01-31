<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function notification(Request $request){
        $agent = $request->header('User-Agent');
        
        if($agent == 'Veritrans' && $request->status_code == 200){
            if($request->payment_type == 'credit_card'){
                Billing::where('uuid', $request->order_id)
                ->update([
                    'payment_method' => $request->payment_type, 
                    'settlement_time' => $request->transaction_time, 
                    'status' => 'paid'
                ]);

                return response()->json([
                    'success' => true
                ]); 

            } else {
                Billing::where('uuid', $request->order_id)
                ->update([
                    'payment_method' => $request->payment_type, 
                    'settlement_time' => $request->settlement_time, 
                    'status' => 'paid'
                ]);

                return response()->json([
                    'success' => true
                ]); 
            }
            
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, update status pembayaran gagal!'
            ]); 
        }

              
    }
}
