<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Device;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function status(Request $request) 
    {
        $request->validate([
            'ack' => 'required',
            'timestamp' => 'required'   
        ]);

        if(!empty($request->uuid)){
            Message::where('uuid', $request->uuid)
            ->update([
                'ack' => $request->ack,
                'timestamp' => $request->timestamp,
                'message_id' => $request->message
            ]);
        } else {
            Message::where('message_id', $request->id)
            ->update([
                'ack' => $request->ack,
                'timestamp' => $request->timestamp
            ]);
        }       

        return response()->json([
            'success' => true
        ]); 
    }

    public function blast(){
        // $client = new \GuzzleHttp\Client();
        $blasts = Message::where('type', 'blast')->where('ack', 0)->get()->unique('user_id');
        // dd($blasts);

        if (!$blasts->isEmpty()) { 
            foreach($blasts as $blast){
                $device = Device::find($blast->device_id);
                $contact = Contact::find($blast->contact_id);
                // $response = $client->request('POST', env('BLAST_URL'), [
                //     'headers' => [
                //         'Content-Type' => 'application/x-www-form-urlencoded',
                //     ],
                //     'form_params' => [
                //         'message' => $blast->message,
                //         'device' => $device->uuid,
                //         'receiver' => $contact->phone
                //     ]
                // ]);
                $response = Http::timeout(240)->asForm()->post(env('BLAST_URL'), [
                    'message' => $blast->message,
                    'device' => $device->uuid,
                    'receiver' => $contact->phone,
                ]);
                
                $result = json_decode($response->getBody());
                Message::where('uuid', $blast->uuid)
                ->update([
                    'message_id' => $result->response->id->id,
                    'timestamp' => $result->response->timestamp
                ]);
            }
        }
    }

    public function send(){
        $messages = Message::where('type', 'single')->where('ack', 0)->get()->unique('user_id');
        $response = [];

        if (!$messages->isEmpty()) { 
            foreach($messages as $message){
                $device = Device::find($message->device_id);
                $contact = Contact::find($message->contact_id);
                $data = [
                    'uuid' => $message->uuid,
                    'message' => $message->message,
                    'device' => $device->uuid,
                    'receiver' => $contact->phone,
                    'file' => $message->file
                ];
                array_push($response, $data);
            }
            return response()->json([
                'success' => true,
                'message' => $response
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Tidak ada pesan yang akan dikirim"
            ]);
        }

        
    }

    public function contact(Request $request) 
    {
        $request->validate([
            'id' => 'required',
            'phone' => 'required'
        ]);

        $device = Device::where('uuid', $request->id)->first();
        $contact = Contact::where('user_id', $device->user_id)->where('phone', $request->phone)->count();
        if($contact == 0){
            $contact = Contact::create([
                'phone' => $request->phone,
                'user_id' => $device->user_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Contact berhasil disimpan'
            ]); 
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Contact sudah ada'
            ]); 
        }       
    }
}
