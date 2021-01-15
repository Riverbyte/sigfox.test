<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Message;
use Illuminate\Support\Facades\DB;
use App\Models\Device;

class MessageComponent extends Component
{

    protected function getListeners()
    {
        return ['postAdded' => 'incrementPostCount'];
    }

    public $alerta;

    public function render()
    {
        $messages = Message::join("devices","messages.DEVICE_ID","=","devices.ID")->select(DB::raw("messages.ID, devices.NAME,devices.DEVICE, messages.DATA, messages.TIME"))->orderBy('messages.ID','desc')->get();

       // $messages = Message::latest('id')->get();
        return view('livewire.message-component', compact('messages'));
        
    }


    public function incrementPostCount()
    {
        $this->alerta = '5';
    }
 

}
