<?php

namespace App\Console\Commands;

use App\Models\Contact;
use App\Models\Device;
use App\Models\Message;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
        $blasts = Message::where('type', 'blast')->where('ack', 0)->get()->unique('user_id');

        if (!$blasts->isEmpty()) { 
            foreach($blasts as $blast){
                $device = Device::find($blast->device_id);
                $contact = Contact::find($blast->contact_id);
                $response = Http::timeout(240)->asForm()->post(env('BLAST_URL'), [
                    'message' => $blast->message,
                    'device' => $device->uuid,
                    'receiver' => $contact->phone,
                ]);
                
                $result = json_decode($response->getBody());
                Message::where('uuid', $blast->uuid)
                ->update([
                    'message_id' => $result->response->id->id,
                    'timestamp' => $result->response->timestamp,
                    'ack' => $result->response->ack
                ]);
            }
        }
        
    }
}
