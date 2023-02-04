<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Device;
use App\Models\Group;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // dd(Auth::user());
        $message = Message::where('user_id', Auth::user()->id)->count();
        $contact = Contact::where('user_id', Auth::user()->id)->count();
        $group = Group::where('user_id', Auth::user()->id)->count();
        $device = Device::where('user_id', Auth::user()->id)->where('status', 'connected')->count();
        return view('home', compact('message', 'contact', 'group', 'device'));
    }
}
