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
    public $perPage = '5';

    protected $queryString = [
        'q' => ['except' => ''],
        'sortBy' => ['except' => 'id'],
        'sortAsc' => ['except' => false],
        'perPage'
    ];

    use WithPagination;   

    public function render()
    {
        $messages = Message::join("devices","messages.DEVICE_ID","=","devices.ID")->
        select(DB::raw("messages.ID, devices.NAME,devices.DEVICE, messages.DATA, messages.TIME"))
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
        /*
        $devices = Device::when( $this->q, function($query) {
            return $query->where(function( $query) {
                $query->where('name', 'like', '%'.$this->q . '%')
                    ->orWhere('device', 'like', '%' . $this->q . '%')
                    ->orWhere('description', 'like', '%' . $this->q . '%')
                    ->orWhere('user',$this->q);
            });
        })
        ->when($this->active, function( $query) {
            return $query->active();
        })
        ->orderBy( $this->sortBy, $this->sortAsc ? 'ASC' : 'DESC');
*/
       // $messages = Message::latest('id')->get();
        return view('livewire.message-component', compact('messages'));
        
    }

    public function sortBy( $field) 
    {
        if( $field == $this->sortBy) {
            $this->sortAsc = !$this->sortAsc;
        }
        $this->sortBy = $field;
    }

 

}
