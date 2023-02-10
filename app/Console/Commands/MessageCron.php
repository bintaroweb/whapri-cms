<?php

namespace App\Console\Commands;

use App\Models\Contact;
use App\Models\Device;
use App\Models\Message;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class MessageCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'message:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $messages = Message::where('type', 'single')->where('ack', 0)->get()->unique('user_id');

        if (!$messages->isEmpty()) { 
            foreach($messages as $message){
                $device = Device::find($message->device_id);
                $contact = Contact::find($message->contact_id);

                $response = Http::timeout(240)->asForm()->post(env('MESSAGE_URL'), [
                    'uuid' => $message->uuid,
                    'message' => $message->message,
                    'device' => $device->uuid,
                    'receiver' => $contact->phone,
                    'file' => $message->file
                ]);

                $result = json_decode($response->getBody());
                Message::where('uuid', $message->uuid)
                ->update([
                    'message_id' => $result->response->id->id,
                    'timestamp' => $result->response->timestamp,
                    'ack' => $result->response->ack
                ]);
                
                // $data = [
                //     'uuid' => $message->uuid,
                //     'message' => $message->message,
                //     'device' => $device->uuid,
                //     'receiver' => $contact->phone,
                //     'file' => $message->file
                // ];
                // array_push($response, $data);
            }
        }

        return Command::SUCCESS;
    }
}
