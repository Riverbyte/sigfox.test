<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Device;

class DeviceComponent extends Component
{

    public $device, $name, $description, $user, $device_id;

    public $accion = 'store';

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
