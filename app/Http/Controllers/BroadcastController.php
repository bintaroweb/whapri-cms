<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Device;
use App\Models\Group;
use App\Models\Message;
use App\Models\Template;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Foreach_;

class BroadcastController extends Controller
{
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
        return view('broadcast.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable()
    {
        $messages = $messages = DB::table('messages')
                        ->join('groups', 'messages.group_id', '=', 'groups.id')
                        ->join('contacts', 'messages.contact_id', '=', 'contacts.id')
                        ->join('devices', 'messages.device_id', '=', 'devices.id')
                        ->select('messages.*', 'contacts.phone as contact', 'contacts.name as contact_name', 'groups.name as group', 'devices.name as device')
                        ->where('messages.type', '=', 'blast' )
                        ->where('messages.deleted_at', '=', null )
                        ->where('devices.deleted_at', '=', null )
                        ->orderBy('messages.created_at', 'desc')
                        ->get();
        $data= [];

        foreach($messages as $message){
            $message->date = \Carbon\Carbon::parse($message->created_at)->format('d M Y H:i:s');

            if(strlen($message->message) > 150) {
                $string = substr($message->message, 0, 150).'...';
                $message->message = $string;
            } else {
                $message->message = $message->message;
            }

            if(!empty($message->contact_name)){
                $message->name = $message->contact . ' (' . $message->contact_name . ')';
            } else {
                $message->name  = $message->contact;
            }

            $message->file = $message->file;
            $message->ack = $message->ack;

            // $user = User::where('id', $message->user_id)->first();
            // $message['user'] = $user->name;

            // $device = Device::where('id', $message->device_id)->first();
            $message->device = $message->device;

            // $group = Group::where('id', $message->group_id)->first();
            $message->group = $message->group;

            $message->time = date("d-m-Y H:i:s", $message->timestamp); 

            array_push($data, $message);
        }


        return DataTables::of($data)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function autocomplete(Request $request)
    {
        $data = Group::where('user_id', Auth::user()->id)->get();
        return response()->json($data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $client = new \GuzzleHttp\Client();
        // $blasts = Message::where('type', 'blast')->where('ack', 0)->get()->unique('user_id');

        // foreach($blasts as $blast){
        //     $device = Device::find($blast->device_id);
        //     $contact = Contact::find($blast->contact_id);
        //     $response = $client->request('POST', env('BLAST_URL'), [
        //         'headers' => [
        //             'Content-Type' => 'application/x-www-form-urlencoded',
        //         ],
        //         'form_params' => [
        //             'message' => $blast->message,
        //             'device' => $device->uuid,
        //             'receiver' => $contact->phone
        //         ]
        //     ]);
        // }
        // $blast = Message::where('type', 'blast')->where('ack', 0)->get()->unique('user_id');
        // dd($blast);

        $devices = Device::where('user_id', Auth::user()->id )->get();
        $templates = Template::where('user_id', Auth::user()->id )->get();   
        return view('broadcast.create', ['devices' => $devices, 'templates' => $templates]);
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
            'message' => 'required|string',
            'receiver'  => 'required|string',
            'device' => 'required|string',
        ]);

        $device = Device::where('uuid', $request['device'])->first();
        $contacts = Contact::where('group_id', $request['receiver'])
                            ->where('user_id', Auth::user()->id)
                            ->get();

        // dd($contacts);

        foreach($contacts as $contact){
            Message::create([
                'message' => $request['message'],
                'message_id' => $request['message_id'],
                'contact_id' => $contact->id,
                'device_id' => $device->id,
                'user_id' => Auth::user()->id,
                'group_id' => $request['receiver'],
                'type' => 'blast',
                'ack' => 0,
            ]);    
        }

        
        return redirect('/broadcasts')->with('success', 'Pesan berhasil ditambah');
    }

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
    public function destroy($id)
    {
        //
    }
}
