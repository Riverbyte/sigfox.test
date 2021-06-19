<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Device;
use Livewire\WithPagination;
use GuzzleHttp\Client;
use App\Models\Event;


class DevicesAdminComponent extends Component
{
    use WithPagination; 

    public $device, $name, $description, $user, $device_id, $pac;
    public $event_name, $email_checkbox = 0, $email_destination, $message_checkbox = 0, $message_destination, $call_checkbox = 0, $call_destination, $email_content, $message_content, $call_content, $event_mail_id, $event_call_id, $event_msg_id;

    public $deviceTypeId = '5f4e7ec5c563d604790a8711';


    public $permisos;
    public $active;
    public $q;
    public $sortBy = 'id';
    public $sortAsc = true;
    public $item;
    public $perPage = '25';
 
    public $confirmingItemDeletion = false;
    public $confirmingItemSuspend = false;
    public $confirmingItemAdd = false;
    public $confirmingEventAdd = false;


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
        $this->user_id = auth()->user()->id;
        $admin = auth()->user()->hasRole('Admin');

        $devices = Device::when($admin != 1, function( $query) {
                     return $query->where('user', $this->user_id );
                })
                ->when( $this->q, function($query) {
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
                "activable" => true,
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

    public function confirmItemSuspend( $id)
    {
        $this->confirmingItemSuspend = $id;
    }



    public function suspendItem(Device $device)
    {
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://api.sigfox.com/v2/',
            // You can set any number of default request options.
            "auth" => [$this->username, $this->password],
            'timeout'  => 2.0,
        ]);
        
        $request_param = [
            "data" => [$device->device]
        ];
        try {
            $res = $client->request("POST", "devices/bulk/suspend", ['http_errors' => false, 'json' => $request_param]);
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
            session()->flash('message', 'Item Suspended Successfully');
            session()->flash('alert-class', 'alert-succes'); 
        }
        

        $this->confirmingItemSuspend = false;
        
    }


    public function enableItem(Device $device)
    {
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://api.sigfox.com/v2/',
            // You can set any number of default request options.
            "auth" => [$this->username, $this->password],
            'timeout'  => 2.0,
        ]);
        
        $request_param = [
            "data" => [$device->device]
        ];
        try {
            $res = $client->request("POST", "devices/bulk/resume", ['http_errors' => false, 'json' => $request_param]);
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
            session()->flash('message', 'Item Resumed Successfully');
            session()->flash('alert-class', 'alert-succes'); 
        }
        

        $this->confirmingItemSuspend = false;
        
    }



    public function confirmEventEdit(Device $device) 
    {
        $this->resetErrorBag();
        $this->reset(['event_name','email_destination','message_destination','call_destination','email_content','message_content','call_content', 'event_mail_id','event_msg_id', 'device_id','event_call_id','email_checkbox','message_checkbox','call_checkbox' ]);

        foreach ($device->events as $key => $event) {
            if ($event->name == 'EMAIL') {
                $this->email_destination = $event->destination;
                $this->email_content = $event->content;
                $this->email_checkbox = $event->active;
                $this->event_mail_id = $event->id;
            }
            else
            if ($event->name == 'MESSAGE') {
                $this->message_destination = $event->destination;
                $this->message_content = $event->content;
                $this->message_checkbox = $event->active;
                $this->event_msg_id = $event->id;
            }
            else
            if ($event->name == 'CALL') {
                $this->call_destination = $event->destination;
                $this->call_content = $event->content;
                $this->call_checkbox = $event->active;
                $this->event_call_id = $event->id;
            }
            
        }

        $this->device_id = $device->id;
        $this->confirmingEventAdd = true;
        
    }


    public function saveEvent()
    {
        $e_ERROR = 0;
        $e_ERROR_MESSAGE = '';
        if( isset( $this->event_mail_id) && $this->email_checkbox) 
        {
            if($this->email_destination == '' || $this->email_content == '')
            {
                $e_ERROR = 1;
                $e_ERROR_MESSAGE = 'Es obligatorio el correo y el contenido, no se guardaron los cambios';
            }
            else
            {
                $event = Event::find($this->event_mail_id);
                $event->update([
                    'destination' => $this->email_destination,
                    'content' => $this->email_content,
                    'active' => $this->email_checkbox,
                    'device_id' => $this->device_id
                ]);
            }
        }
        else
        if ($this->email_checkbox) 
        {
    
            if( isset( $this->email_destination) && isset( $this->email_content)) 
            {
                Event::create([
                    'name' => 'EMAIL',
                    'destination' => $this->email_destination,
                    'content' => $this->email_content,
                    'active' => $this->email_checkbox,
                    'device_id' => $this->device_id
                ]);
            }
            else
            if($this->email_destination == '' || $this->email_content == '')
            {
                $e_ERROR = 1;
                $e_ERROR_MESSAGE = 'Es obligatorio el correo y el contenido, no se guardaron los cambios';
                
            }
        }


        if( isset( $this->event_msg_id) && $this->message_checkbox) 
        {
            if($this->message_destination == '' || $this->message_content == '')
            {
                $e_ERROR = 1;
                $e_ERROR_MESSAGE = 'Es obligatorio el telefono y el mensaje en el evento MESSAGE, no se guardaron los cambios';
            }
            else
            {
                $event = Event::find($this->event_msg_id);
                $event->update([
                    'destination' => $this->message_destination,
                    'content' => $this->message_content,
                    'active' => $this->message_checkbox,
                    'device_id' => $this->device_id
                ]);
            }
        }
        else
        if ($this->message_checkbox) 
        {
          
            if( isset( $this->message_destination) && isset( $this->message_content)) 
            {
                Event::create([
                    'name' => 'MESSAGE',
                    'destination' => $this->message_destination,
                    'content' => $this->message_content,
                    'active' => $this->message_checkbox,
                    'device_id' => $this->device_id
                ]);
            }
            else
            if($this->message_destination == '' || $this->message_content == '')
            {
                $e_ERROR = 1;
                $e_ERROR_MESSAGE = 'Es obligatorio el telefono y el mensaje en el evento MESSAGE, no se guardaron los cambios';
            }  
        }      
        

        if( isset( $this->event_call_id) && $this->call_checkbox) 
        {
            if($this->call_destination == '' || $this->call_content == '')
            {
                $e_ERROR = 1;
                $e_ERROR_MESSAGE = 'Es obligatorio el telefono y el mensaje en el evento CALL, no se guardaron los cambios';
            }
            else
            {
                $event = Event::find($this->event_call_id);
                $event->update([
                    'destination' => $this->call_destination,
                    'content' => $this->call_content,
                    'active' => $this->call_checkbox,
                    'device_id' => $this->device_id
                ]);
            }
        }
        else
        if ($this->call_checkbox) 
        {
            if( isset( $this->call_destination) && isset( $this->call_content)) 
            {
                Event::create([
                    'name' => 'CALL',
                    'destination' => $this->call_destination,
                    'content' => $this->call_content,
                    'active' => $this->call_checkbox,
                    'device_id' => $this->device_id
                ]);
            }
            else
            if($this->call_destination == '' || $this->call_content == '')
            {
                $e_ERROR = 1;
                $e_ERROR_MESSAGE = 'Es obligatorio el telefono y el mensaje en el evento CALL, no se guardaron los cambios';
                
            }
        }





        
        if($e_ERROR)
        {
            session()->flash('message', $e_ERROR_MESSAGE);
            session()->flash('alert-class', 'alert-danger'); 
        }
        else
        {
            session()->flash('message', 'Item Updated Successfully');
            session()->flash('alert-class', 'alert-succes'); 
        }
        

        $this->confirmingEventAdd = false;
        $this->reset(['event_name','email_destination','message_destination','call_destination','email_content','message_content','call_content', 'event_mail_id','event_msg_id', 'device_id','event_call_id','email_checkbox','message_checkbox','call_checkbox' ]);

    }


}


