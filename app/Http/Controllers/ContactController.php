<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('contact.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable()
    {
        $contacts = Contact::where('user_id', Auth::user()->id)->get();
        return DataTables::of($contacts)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {     
        return view('contact.create');
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
            'name' => 'nullable|string',            
            'phone' => 'required|string'          
        ]);

        $contact = Contact::create([
            'name' => $request['name'],
            'phone' => $request['phone'],
            'label' => $request['label'],
            'user_id' => Auth::user()->id,
        ]);

        $contactId = $contact->id;

        if($request->ajax()){
            return response()->json([
                'success' => true,
                'id' => $contactId
            ]);
        }         

        return redirect('/contacts')->with('success', 'Kontak berhasil ditambah');
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
    public function edit($uuid)
    {
        $data = Contact::where('uuid', $uuid)->first();
        return view('contact.edit', ['contact' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uuid)
    {
        $request->validate([
            'name'   => 'nullable|string',
            'phone'  => 'required|string',
            'label'  => 'nullable|string'           
        ]);

        Contact::where('uuid', $uuid)
                ->update([
                    'name'  => $request->name, 
                    'phone' => $request->phone,
                    'label' => $request->label
                ]);
        
         return redirect('/contacts')->with('success', 'Kontak berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        Contact::where('uuid', $uuid)->delete();
        return redirect('/contacts')->with('success', 'Kontak berhasil dihapus');
    }
}
