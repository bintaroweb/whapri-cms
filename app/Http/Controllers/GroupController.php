<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('group.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable()
    {
        $groups = Group::where('user_id', Auth::user()->id)->get();
        return DataTables::of($groups)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function autocomplete(Request $request)
    {
        $data = Contact::where('user_id', Auth::user()->id)->where('group_id', null)->get();    
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('group.create');
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
            'name' => 'required|string',            
            'phones' => 'required|array',
            'label' => 'nullable|string'          
        ]);

        $phones = $request->phones;
        $total = count($phones);

        $group = Group::create([
            'name' => $request['name'],
            'total' => $total,
            'label' => $request['label'],
            'user_id' => Auth::user()->id,
        ]);

        $groupId = $group->id;
        
        foreach($phones as $phone){
            Contact::where('uuid', $phone)
                ->update([
                    'group_id' => $groupId
                ]);
        }

        if($request->ajax()){
            return response()->json([
                'success' => true,
                'id' => $groupId,
                'name' => $request->name
            ]);
        }         

        return redirect('/groups')->with('success', 'Group berhasil ditambah');
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
        $group = Group::where('uuid', $uuid)->first();
        $phones = Contact::where('group_id', $group->id)->get();

        return view('group.edit', ['group' => $group, 'phones' => $phones]);
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
            'name' => 'required|string',            
            'phones' => 'required|array',
            'label' => 'nullable|string'          
        ]);

        Group::where('uuid', $uuid)
                ->update([
                    'name' => $request->name, 
                    'label' => $request->label
                ]);
        

        $group = Group::where('uuid', $uuid)->first();
        $contacts = Contact::where('group_id', $group->id)->get();

        foreach ($contacts as $contact) {
            Contact::where('group_id', $group->id)-> update([
                'group_id' => null
            ]);
        }

        $phones = $request->phones;

        foreach($phones as $phone) {
            Contact::where('uuid', $phone)
                ->update([
                    'group_id' => $group->id
                ]);
        }

        return redirect('/groups')->with('success', 'Group berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $group = Group::where('uuid', $uuid)->first();
        Contact::where('group_id', $group->id)-> update([
            'group_id' => null
        ]);

        Group::where('uuid', $uuid)->delete();

        return redirect('/groups')->with('success', 'Pesan berhasil dihapus');
    }
}
