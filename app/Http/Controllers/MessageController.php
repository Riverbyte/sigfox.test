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

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.messages.index'); 
    }


    public function store(Request $request){

        $e_VOLTAGE = 0;
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

        $a_DATA = str_split($request->data, 2);
        
        /////// si el evento comienza con 00 es un evento de estatus generico o 04 es Heartbeat no se diaparan los eventos configurados del dispositivo
        if( $a_DATA[0] != '00' && $a_DATA[0] != '04'  )
        {
            foreach ($device->events as $key => $event) 
            {
                if ($event->name == 'EMAIL' && $event->destination != '' && $event->content != '' && $event->active == 1) 
                {
                    Mail::to($event->destination)->send(new MensajeRecibidoMailable($message, $device,$event->content));
                }
                else
                if ($event->name == 'MESSAGE' && $event->destination != '' && $event->content != '' && $event->active == 1)  
                {
                    $BulkSmsController = new BulkSmsController;
                    $BulkSmsController->sendSms($event->destination,$event->content);
                }
                else
                if ($event->name == 'CALL' && $event->destination != '' && $event->content != '' && $event->active == 1)  
                {
                    $BulkSmsController = new BulkSmsController;
                    $BulkSmsController->call($event->destination,$event->content);
                }
            }

        }
        else  /////// se revisa el voltaje de la bateria
        if( $a_DATA[0] == '00' || $a_DATA[0] == '04')
        {
            $e_VOLTAGE = hexdec($a_DATA[1].$a_DATA[2]) / 1000;
            if($e_VOLTAGE <= 2.0)
            {
                $a_AVISO = array();
                if($device->alert_json)
                {
                    $a_AVISO = json_decode($device->alert_json,1);
                }
                
                if(!in_array('low battery',$a_AVISO))
                {
                    $a_AVISO[] = 'low battery';
                    $device->update([
                        'alert' => 1,
                        'alert_json' => json_encode($a_AVISO)
                    ]);
                }

                return $a_AVISO;
            }


            return $e_VOLTAGE;

        }



        //$device = Device::find($request->device);

        
        
        return $device->name;
    }

/*
    public function show($id)
    {
        $messages = Message::join("devices","messages.DEVICE_ID","=","devices.ID")->select(DB::raw("messages.ID, devices.NAME,devices.DEVICE, messages.DATA, messages.TIME"))->where('device_id',$id)->orderBy('messages.ID','desc')->paginate();

        return view('messages.show', compact('messages'));
        //return view('livewire.message-component', compact('messages'));
    }
*/

}
