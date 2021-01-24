<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Device;
use Livewire\WithPagination;

class DevicesAdminComponent extends Component
{
    use WithPagination; 

    public $device, $name, $description, $user, $device_id;


    public $active;
    public $q;
    public $sortBy = 'id';
    public $sortAsc = true;
    public $item;
    public $perPage = '5';
 
    public $confirmingItemDeletion = false;
    public $confirmingItemAdd = false;
    

    protected $queryString = [
        'q' => ['except' => ''],
        'sortBy' => ['except' => 'id'],
        'sortAsc' => ['except' => true],
        'perPage'
    ];
 
    protected $rules = [
        'device' => 'required',
        'name' => 'required|string|min:4',
        'description' => 'required'
    ];

   
    public function render()
    {
        $user_auth = auth()->user()->id;
        

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


        //     $devices = Device::when($user_auth == 1, function( $query) {
        //         return $query->where('user', $user_auth );
        //     })->
        //     when( $this->q, function($query) {
        //     return $query->where(function( $query) {
        //         $query->where('name', 'like', '%'.$this->q . '%')
        //             ->orWhere('device', 'like', '%' . $this->q . '%')
        //             ->orWhere('description', 'like', '%' . $this->q . '%')
        //             ->orWhere('user',$this->q);
        //     });
        // })
        // ->when($this->active, function( $query) {
        //     return $query->active();
        // })
        // ->orderBy( $this->sortBy, $this->sortAsc ? 'ASC' : 'DESC');



 
        $query = $devices->toSql();
        $devices = $devices->paginate( $this->perPage );
        
        

        return view('livewire.devices-admin-component', [
            'devices' => $devices,
            'query' => $query,
            'user_auth' => $user_auth,
        ]);

        //$devices = Device::latest('id')->get();
        //return view('livewire.device-component', compact('devices'));
    }

    public function updatingActive() 
    {
        $this->resetPage();
    }
 
    public function updatingQ() 
    {
        $this->resetPage();
    }
 
    public function sortBy( $field) 
    {
        if( $field == $this->sortBy) {
            $this->sortAsc = !$this->sortAsc;
        }
        $this->sortBy = $field;
    }
 
    public function confirmItemDeletion( $id) 
    {
        $this->confirmingItemDeletion = $id;
    }


    public function destroy(Device $device)
    {
        $device->delete();
        $this->confirmingItemDeletion = false;
        session()->flash('message', 'Item Deleted Successfully');
    }


    public function confirmItemAdd() 
    {
        $this->reset(['device','name','description','user', 'device_id' ]);
        $this->confirmingItemAdd = true;
    }


    public function saveItem() 
    {
        $this->validate();
 
        
        if( isset( $this->device_id)) {
            $device = Device::find($this->device_id);
            $device->update([
                'device' => $this->device,
                'name' => $this->name,
                'description' => $this->description,
                'user' => $this->user
            ]);
            session()->flash('message', 'Item Saved Successfully');
        } else {
            Device::create([
            'device' => $this->device,
            'name' => $this->name,
            'description' => $this->description,
            'user' => $this->user
            ]);
            session()->flash('message', 'Item Added Successfully');
        }
 

        $this->confirmingItemAdd = false;
        $this->reset(['device','name','description','user', 'device_id' ]);
 
    }



 
    public function confirmItemEdit(Device $device) 
    {
        $this->resetErrorBag();
        $this->device = $device->device;
        $this->name = $device->name;
        $this->description = $device->description;
        $this->user = $device->user;
        $this->device_id = $device->id;
        $this->confirmingItemAdd = true;
    }
}
