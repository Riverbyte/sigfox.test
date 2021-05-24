<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Message;
use Illuminate\Support\Facades\DB;
use App\Models\Device;
use Livewire\WithPagination;

class MessageComponent extends Component
{
    public $q;
    public $sortBy = 'id';
    public $sortAsc = false;
    public $item;
    public $perPage = '25';
    public $device_id;

    protected $queryString = [
        'q' => ['except' => ''],
        'sortBy' => ['except' => 'id'],
        'sortAsc' => ['except' => false],
        'perPage'
    ];

    use WithPagination;   

    public function render()
    {
        $this->user_id = auth()->user()->id;
        $admin = auth()->user()->hasRole('Admin');


        $messages = Message::join("devices","messages.DEVICE_ID","=","devices.ID")
        ->join("users","users.ID","=","devices.USER")
        ->select(DB::raw("messages.ID, devices.NAME,devices.DEVICE, messages.DATA, messages.TIME"))
        ->when($admin != 1, function( $query) {
            return $query->where('users.ID', $this->user_id );
        })
        ->when($this->device_id, function( $query) {
            return $query->where('device_id', $this->device_id );
        })
        ->when( $this->q, function($query) {
            return $query->where(function( $query) {
                $query->where('devices.NAME', 'like', '%'.$this->q . '%')
                    ->orWhere('devices.DEVICE', 'like', '%' . $this->q . '%')
                    ->orWhere('messages.DATA', 'like', '%' . $this->q . '%');
            });
        })
        ->orderBy( $this->sortBy, $this->sortAsc ? 'ASC' : 'DESC');
        $query = $messages->toSql();
        $messages = $messages->paginate( $this->perPage );

        $device = '';
        if ($this->device_id) {
            $device = Device::find($this->device_id);
        }
        

       // $messages = Message::latest('id')->get();
        return view('livewire.message-component', compact('messages','device','admin'));
        
    }

    public function sortBy( $field) 
    {
        if( $field == $this->sortBy) {
            $this->sortAsc = !$this->sortAsc;
        }
        $this->sortBy = $field;
    }

 

}
