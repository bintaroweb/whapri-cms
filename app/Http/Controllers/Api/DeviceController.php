<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function status(Request $request) 
    {
        $request->validate([
            'uuid' => 'required|string',
            'status' => 'required|string'       
        ]);

        Device::where('uuid', $request->uuid)
            ->update([
                'status' => $request->status
            ]);

        return response()->json([
            'success' => true,
            'status' => $request->status
        ]); 


    }
}
