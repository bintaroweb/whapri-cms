<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function update_message(Request $request) 
    {
        $request->validate([
            'message_id' => 'required|string',
            'time'  => 'required|string',
            'ack' => 'required|string',
            'token' => 'required'
        ]);

        if($request->token != 'C238C36DB8EBAEC5E4E4278159D424CE16389896F5F50B4A131DAC7FEF217130'){
            return response()->json([
                'response_code' => 404,
                'status' => false,
                'message' => 'Token salah'
            ]);
        } else {
            Message::where()
        }
    }

    public function payment_status(Request $request){
        if($request->status_code == 200){
            Billing::where('uuid', $request->order_id)
            ->update([
                'payment_method' => $request->payment_type, 
                'status' =>'Paid'
            ]);
        } 

        return response()->json([
            'success' => true
        ]);       
    }
}
