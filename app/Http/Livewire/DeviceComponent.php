<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Device;
use Livewire\WithPagination;

class DeviceComponent extends Component
{
    use WithPagination;  
    
    public $active;
    public $q;
    public $sortBy = 'id';
    public $sortAsc = true;
    public $item;
 
    public $confirmingItemDeletion = false;
    public $confirmingItemAdd = false;



    public $device, $name, $description, $user, $device_id;

    public $accion = 'store';

    protected $queryString = [
        'active' => ['except' => false],
        'q' => ['except' => ''],
        'sortBy' => ['except' => 'id'],
        'sortAsc' => ['except' => true],
    ];
 
    protected $rules = [
        'item.name' => 'required|string|min:4',
        'item.price' => 'required|numeric|between:1,100',
        'item.status' => 'boolean'
    ];



    public function render()
    {
        $devices = Device::latest('id')->get();
        return view('livewire.device-component', compact('devices'));
    }

    public function store(){
        Device::create([
            'device' => $this->device,
            'name' => $this->name,
            'description' => $this->description,
            'user' => $this->user
        ]);

        $this->reset(['device','name','description','user' ]);
    }


    public function edit(Device $device)
    {
        $this->device = $device->device;
        $this->name = $device->name;
        $this->description = $device->description;
        $this->user = $device->user;
        $this->device_id = $device->id;
        $this->accion = 'update';

       
    }

    public function update()
    {
        $device = Device::find($this->device_id);

        $device->update([
            'device' => $this->device,
            'name' => $this->name,
            'description' => $this->description,
            'user' => $this->user
        ]);

        $this->reset(['device','name','description','user' ]);
        $this->accion = 'store';

    }

    public function default()
    {
        $this->reset(['device','name','description','user' ]);
        $this->accion = 'store';
    }

    public function destroy(Device $device)
    {
        $device->delete();
    }



}
