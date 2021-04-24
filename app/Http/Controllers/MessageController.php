<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Device;
use App\Models\Message;
use Illuminate\Support\Facades\Mail;
use App\Mail\MensajeRecibidoMailable;
use App\Http\Controllers\BulkSmsController;

class MessageController extends Controller
{
    public function store(Request $request){

        
        $e_FECHA = date('Y-m-d\TH:i:s', $request->time);

        //$device = DB::table('devices')->where('device', $request->device)->first();
        $device = Device::where('device', $request->device)->first();

        $message = new Message();

        $message->device_id = $device->id;
        $message->time = $e_FECHA;
        $message->seq_num = $request->seqNumber;
        $message->data = $request->data;
        $message->device_type_id = $request->deviceTypeId;
        
        $message->save();

        foreach ($device->events as $key => $event) 
        {
            if ($event->name == 'EMAIL') 
            {
                Mail::to($event->destination)->send(new MensajeRecibidoMailable($message, $device,$event->content));
            }
            else
            if ($event->name == 'MESSAGE') 
            {
                $BulkSmsController = new BulkSmsController;
                $BulkSmsController->sendSms($event->destination,$event->content);
            }
            else
            if ($event->name == 'CALL') 
            {
                $BulkSmsController = new BulkSmsController;
                $BulkSmsController->call($event->destination,$event->content);
            }
        }

        //$device = Device::find($request->device);

        
        
        //return $device->name;
    }


    public function show($id)
    {
        $messages = Message::join("devices","messages.DEVICE_ID","=","devices.ID")->select(DB::raw("messages.ID, devices.NAME,devices.DEVICE, messages.DATA, messages.TIME"))->where('device_id',$id)->orderBy('messages.ID','desc')->paginate();

        return view('messages.show', compact('messages'));
    }


}
