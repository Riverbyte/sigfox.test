<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Device;
use App\Models\Message;

class MessageController extends Controller
{
    public function store(Request $request){
        $e_FECHA = date('Y-m-d\TH:i:s', $request->time);

        $device = DB::table('devices')->where('device', $request->device)->first();

        $message = new Message();

        $message->device_id = $device->id;
        $message->time = $e_FECHA;
        $message->seq_num = $request->seqNumber;
        $message->data = $request->data;
        $message->device_type_id = $request->deviceTypeId;
        
        $message->save();
        
        //return $device->name;
    }
}
