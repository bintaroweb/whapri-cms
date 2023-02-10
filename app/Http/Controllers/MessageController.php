<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Device;
use App\Models\Message;
use App\Models\Template;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DateTime;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
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
        return view('message.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable()
    {
        $messages = DB::table('messages')
                        ->join('contacts', 'messages.contact_id', '=', 'contacts.id')
                        ->join('devices', 'messages.device_id', '=', 'devices.id')
                        ->select('messages.*', 'contacts.phone as contact', 'contacts.name as contact_name', 'devices.name as device')
                        ->where('messages.type', '=', 'single' )
                        ->where('messages.user_id', '=', Auth::user()->id)
                        ->where('messages.deleted_at', '=', null )
                        ->where('devices.deleted_at', '=', null )
                        ->orderBy('messages.created_at', 'desc')
                        ->get();
        
        $data= [];

        // dd($messages);

        foreach($messages as $message){
            // $message->date = \Carbon\Carbon::parse($message->created_at)->format('d M Y H:i:s');
            $message->date = date("d-m-Y H:i:s", $message->timestamp);

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


            $message->time = date("Y-m-d H:i:s", $message->timestamp); 

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
        $contacts = Contact::where('user_id', Auth::user()->id)->get();
        return response()->json($contacts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $devices = Device::where('user_id', Auth::user()->id )->get(); 
        $templates = Template::where('user_id', Auth::user()->id )->get();        
        return view('message.create', ['devices' => $devices, 'templates' => $templates]);
    }

    public function template(Request $request)
    {
        $template = Template::where('uuid', $request->uuid)->first(); 
         
        return response()->json([
            'success' => true,
            'template' => $template
        ]);  
        
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
            'message' => 'required',
            'receiver'  => 'string',
            'device' => 'required',
            // 'message_id' => 'required',
            // 'ack' => 'required',
            // 'timestamp' => 'required'
        ]);

        // dd($request);

        $device = Device::where('uuid', $request['device'])->first();

        $contact = Contact::where('phone', $request['receiver'])
                            ->where('user_id', Auth::user()->id)
                            ->first();

        //Upload Image Cloudinary
        $img  = $request->file('img');
        $file = Cloudinary::upload($img->getRealPath())->getSecurePath();
        // $result = CloudinaryStorage::upload($img->getRealPath(), $img->getClientOriginalName());

        if(!empty($file)){
            Message::create([
                'message' => $request['message'],
                'contact_id' => $contact->id,
                'device_id' => $device->id,
                'user_id' => Auth::user()->id,
                'ack' => 0,
                'file' => $file,
                'date' => date("Y-m-d"),
                'type' => 'single',
            ]);
        } else {
            Message::create([
                'message' => $request['message'],
                'contact_id' => $contact->id,
                'device_id' => $device->id,
                'user_id' => Auth::user()->id,
                'ack' => 0,
                'date' => date("Y-m-d"),
                'type' => 'single',
            ]);
        }
        

        return redirect('/messages')->with('success', 'Pesan berhasil ditambah');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail(Request $request)
    {
        $message = Message::where('uuid', $request->uuid)->first();

        // if($request->ajax()){
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        // }  
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
        Message::where('uuid', $uuid)->delete();
        return redirect('/messages')->with('success', 'Pesan berhasil dihapus');
    }
}
