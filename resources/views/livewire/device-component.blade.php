


<div class="pt-4 container mx-auto">

  
       
    <div class="bg-white rounded-lg shadow overflow-hidden max-w-4xl mx-auto p-4 mb-6 ">
        
        <div class="mb-4">
            <label for="device" class="form-label mb-2">Device</label>
            <input wire:model='device' id="device" class="form-control " placeholder="Device id" type="text">
        </div>

        <div class="mb-4"> 
            <label for="name" class="form-label mb-2">Device</label>
            <input wire:model='name' id="name" class="form-control " placeholder="Device name" type="text">
        </div>

        <div class="mb-4">
            <label for="description" class="form-label mb-2">description</label>
            <input wire:model='description' id="description" class="form-control " placeholder="Description" type="text">
        </div>

        <div class="mb-4">
            <label for="user" class="form-label mb-2">user</label>
            <input wire:model='user' id="user" class="form-control " placeholder="User" type="text">
        </div>

        <div>
            @if ($accion == 'store')
                <button  wire:click='store' class="bg-blue-500 mb-2 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded ">Guardar</button>
            @else
                <button wire:click='update' class="bg-blue-500 mb-2 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded ">Actualizar</button>
                <button wire:click='default' class="bg-red-500 hover:bg-red-700 text-white font-bold px-4 py-2 rounded ">Cancelar</button>
            @endif
            
        </div>
    </div>
    
    


    <table class="bg-white rounded-lg shadow overflow-hidden max-w-4xl mx-auto ">
        <thead class="bg-gray-50 border-b border-gray-200 ">
            <tr class="text-xs font-medium text-gray-500 uppercase tracking-wider ">
                <th class="px-6 py-3">ID</th>
                <th class="px-6 py-3">device</th>
                <th class="px-6 py-3">name</th>
                <th class="px-6 py-3">description</th>
                <TH class="px-6 py-3">user</TH>
                <TH ></TH>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-200 ">
            @foreach ($devices as $device)
                <tr class="text-gray-500 ">
                    <td class="py-2 px-6">{{$device->id}}</td>
                    <td class="py-2 px-6">
                        <x-jet-nav-link href="{{ route('messages.show',$device ) }}" :active="request()->routeIs('messages')">
                            {{$device->device}}
                        </x-jet-nav-link>
                    </td>
                    <td class="py-2 px-6">{{$device->name}}</td>
                    <td class="py-2 px-6">{{$device->description}}</td>
                    <td class="py-2 px-6">{{$device->user}}</td>
                    <td class="py-2 px-6  ">
                        <button wire:click='edit({{$device}})' class="modal-open bg-blue-500 mb-2 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded w-full ">Editar</button>
                        <button wire:click='destroy({{$device}})' class="bg-red-500 hover:bg-red-700 text-white font-bold px-4 py-2 rounded ">Eliminar</button>
                    </td>
                </tr>
                
            @endforeach

        </tbody>
    </table>
</div>
