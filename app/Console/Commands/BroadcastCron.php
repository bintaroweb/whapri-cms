<?php

namespace App\Console\Commands;

use App\Models\Contact;
use App\Models\Device;
use App\Models\Message;
use Illuminate\Console\Command;

class BroadcastCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'broadcast:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Broadcast Command Executed Successfully!';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = new \GuzzleHttp\Client();
        $blasts = Message::where('type', 'blast')->where('ack', 0)->get()->unique('user_id');

        if (!$blasts->isEmpty()) { 
            foreach($blasts as $blast){
                $device = Device::find($blast->device_id);
                $contact = Contact::find($blast->contact_id);
                $response = $client->request('POST', env('BLAST_URL'), [
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ],
                    'form_params' => [
                        'message' => $blast->message,
                        'device' => $device->uuid,
                        'receiver' => $contact->phone
                    ]
                ]);
                $response->getBody();
            }
        }
        
    }
}
