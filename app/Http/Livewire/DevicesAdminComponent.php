<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Device;
use Livewire\WithPagination;
use GuzzleHttp\Client;

class DevicesAdminComponent extends Component
{
    use WithPagination; 

    public $device, $name, $description, $user, $device_id, $pac;

    public $deviceTypeId = '5f4e7ec5c563d604790a8711';

    public $active;
    public $q;
    public $sortBy = 'id';
    public $sortAsc = true;
    public $item;
    public $perPage = '5';
 
    public $confirmingItemDeletion = false;
    public $confirmingItemAdd = false;

    private $username = '5fadde640499f50eff9068a3';
    private $password = 'ac3cf1e109a4ec9f00b518f5c4c85881';
    public $status = array();
    public $estados_request = array(0 =>'OK', 1 => 'DEAD', 2 => 'OFF_CONTRACT', 3 => 'DISABLED', 4 => 'WARN', 5 => 'DELETED', 6 => 'SUSPENDED', 7 => 'NOT_ACTIVABLE');
    

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
        $this->user = $user_auth;

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
        
        foreach($devices as $device)
        {
            $this->status[$device->device] = 'offline';
        }

        
        
        
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://api.sigfox.com/v2/',
            // You can set any number of default request options.
            "auth" => [$this->username, $this->password],
            'timeout'  => 2.0,
        ]);

        $res = $client->request("GET", "devices");
        $res->getStatusCode();
        $response = json_decode($res->getBody()->getContents());

        foreach($response->data as $device_response)
        {
            $this->status[$device_response->id] = $this->estados_request[$device_response->state];
        }

        //dd($response->data);

        return view('livewire.devices-admin-component', [
            'devices' => $devices,
            'query' => $query,
            'user_auth' => $user_auth,
            'response' => $response->data,
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
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://api.sigfox.com/v2/',
            // You can set any number of default request options.
            "auth" => [$this->username, $this->password],
            'timeout'  => 2.0,
        ]);
        
        try{
            $res = $client->request("DELETE", "devices/". $device->device, ['http_errors' => false]);
        } catch (ClientException $e) {
                
        }
        $res->getStatusCode();
        $response = json_decode($res->getBody()->getContents());

        if( isset($response->message))
        {
            session()->flash('message', $response->message);
            session()->flash('alert-class', 'alert-danger'); 
        }
        else
        {
            $device->delete();
            session()->flash('message', 'Item Deleted Successfully');
            session()->flash('alert-class', 'alert-succes'); 
        }

        $this->confirmingItemDeletion = false;
        
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
            $client = new Client([
                // Base URI is used with relative requests
                'base_uri' => 'https://api.sigfox.com/v2/',
                // You can set any number of default request options.
                "auth" => [$this->username, $this->password],
                'timeout'  => 2.0,
            ]);
            $request_param = [
                "name" => $this->name
            ];
            try {
                $res = $client->request("PUT", "devices/". $this->device, ['http_errors' => false, 'json' => $request_param]);
            } catch (ClientException $e) {
                
            }
            $res->getStatusCode();
            $response = json_decode($res->getBody()->getContents());
            if( isset($response->message))
            {
                session()->flash('message', $response->message);
                session()->flash('alert-class', 'alert-danger'); 
            }
            else
            {
                $device = Device::find($this->device_id);
                $device->update([
                    'device' => $this->device,
                    'name' => $this->name,
                    'description' => $this->description,
                    'user' => $this->user
                ]);
                session()->flash('message', 'Item Updated Successfully');
                session()->flash('alert-class', 'alert-succes'); 
            }
        } else {

            $client = new Client([
                // Base URI is used with relative requests
                'base_uri' => 'https://api.sigfox.com/v2/',
                // You can set any number of default request options.
                "auth" => [$this->username, $this->password],
                'timeout'  => 2.0,
            ]);
                
            $request_param = [
                "deviceTypeId" => '5f4e7ec5c563d604790a8711',
                "id" => $this->device,
                "name" => $this->name,
                "pac" => $this->pac,
                "activable" => false,
                "automaticRenewal" => true,
                "prototype" => true,
                "lat" => '0.0',
                "lng" => '0.0'
            ];
            try {
                $res = $client->request("POST", "devices/", ['http_errors' => false, 'json' => $request_param]);
            } catch (ClientException $e) {
                
            }
            $res->getStatusCode();
            $response = json_decode($res->getBody()->getContents());
            if( isset($response->errors))
            {
                session()->flash('message', $response->errors[0]->message);
                session()->flash('alert-class', 'alert-danger'); 
            }
            else
            {
                Device::create([
                'device' => $this->device,
                'name' => $this->name,
                'description' => $this->description,
                'user' => $this->user
                ]);
                
                session()->flash('message', 'Item Added Successfully');
                session()->flash('alert-class', 'alert-succes'); 
            }
            
            
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
