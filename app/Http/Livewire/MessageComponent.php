<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Message;
use Illuminate\Support\Facades\DB;
use App\Models\Device;
use Livewire\WithPagination;

class MessageComponent extends Component
{
    use WithPagination;   

    public function render()
    {
        $messages = Message::join("devices","messages.DEVICE_ID","=","devices.ID")->select(DB::raw("messages.ID, devices.NAME,devices.DEVICE, messages.DATA, messages.TIME"))->orderBy('messages.ID','desc')->paginate();

       // $messages = Message::latest('id')->get();
        return view('livewire.message-component', compact('messages'));
        
    }

 

}
