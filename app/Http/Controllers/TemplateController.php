<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('template.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable()
    {
        $templates = Template::where('user_id', Auth::user()->id)->get();
        $data= [];

        foreach($templates as $template){
            if(strlen($template->message) > 150) {
                $string = substr($template->message, 0, 150).'...';
                $template['message'] = $string;
            } else {
                $template['message'] = $template->message;
            }

            $template['name'] = $template['name'];

            array_push($data, $templates);
        }
        return DataTables::of($templates)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('template.create');
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
            'message' => 'required|string'        
        ]);

        Template::create([
            'name' => $request['name'],
            'message' => $request['message'],
            'user_id' => Auth::user()->id
        ]);

        return redirect('/templates')->with('success', 'Template berhasil ditambah');
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
        $template = Template::where('uuid', $uuid)->first();
        return view('template.edit', ['template' => $template]);
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
            'message' => 'required|string'        
        ]);

        Template::where('uuid', $uuid)
                ->update([
                    'name' => $request->name, 
                    'message' => $request->message
                ]);
        
        return redirect('/templates')->with('success', 'Template berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        Template::where('uuid', $uuid)->delete();
        return redirect('/templates')->with('success', 'Template berhasil dihapus');
    }
}
