<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

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

    public function import(Request $request)
    {
        $file = $request->file("upload");

        if ($file) {
            $filename = $file->getClientOriginalName();
            //Get extension of uploaded file
            $extension = $file->getClientOriginalExtension(); 
            $tempPath = $file->getRealPath();
            //Get size of uploaded file in bytes
            $fileSize = $file->getSize(); 
            //Check for file extension and size
            $this->checkUploadedFileProperties($extension, $fileSize);
            //Where uploaded file will be stored on the server
            $location = "uploads"; //Created an "uploads" folder for that
            // Upload file
            $file->move($location, $filename);
            // In case the uploaded file path is to be stored in the database
            $filepath = public_path($location . "/" . $filename);
            // Reading file
            $file = fopen($filepath, "r");
            $importData_arr = []; // Read through the file and store the contents as an array
            $i = 0;
            //Read the contents of the uploaded file
            while (($filedata = fgetcsv($file, 1000, ",")) !== false) {
                $num = count($filedata);
                // Skip first row (Remove below comment if you want to skip the first row)
                if ($i == 0) {
                    $i++;
                    continue;
                }
                for ($c = 0; $c < $num; $c++) {
                    $importData_arr[$i][] = $filedata[$c];
                }
                $i++;
            }
            //Close after reading
            fclose($file); 
            $j = 0;
            // dd($importData_arr);
            foreach ($importData_arr as $importData) {
                // dd($importData);
                $j++;

                $phone = str_replace(' ', '', $importData[0]);
                $phone = str_replace('-', '', $phone);
                Contact::create([
                    "name" => $importData[1],
                    "phone" => $phone,
                    "source" => 'import',
                    "user_id" => Auth::user()->id
                ]);
            }
            // return response()->json([
            //     "message" => "$j records successfully uploaded",
            // ]);
            return redirect('/contacts')->with('success', 'Kontak berhasil diimport');
        } else {
            //no file was uploaded
            throw new \Exception(
                "No file was uploaded",
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    public function checkUploadedFileProperties($extension, $fileSize)
    {
        $valid_extension = ["csv"]; //Only want csv and excel files
        $maxFileSize = 2097152; // Uploaded file size limit is 2mb
        if (in_array(strtolower($extension), $valid_extension)) {
            if ($fileSize <= $maxFileSize) {
            } else {
                throw new \Exception(
                    "No file was uploaded",
                    Response::HTTP_REQUEST_ENTITY_TOO_LARGE
                ); //413 error
            }
        } else {
            throw new \Exception(
                "Invalid file extension",
                Response::HTTP_UNSUPPORTED_MEDIA_TYPE
            ); //415 error
        }
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
