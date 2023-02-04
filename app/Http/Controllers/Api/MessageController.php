<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Device;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MessageController extends Controller
{
    public function status(Request $request) 
    {
        $request->validate([
            'id' => 'required|string',
            'ack' => 'required',
            'timestamp' => 'required'   
        ]);

        $message = Message::where('message_id', $request->id)
            ->update([
                'ack' => $request->ack,
                'timestamp' => $request->timestamp
            ]);

        return response()->json([
            'success' => true,
            'status' => $message
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
}
