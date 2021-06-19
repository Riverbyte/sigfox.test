<div class="p-6  bg-white border-b border-gray-200 min-h-screen">
    @if(session()->has('message'))
    <div class=" border {{session('alert-class')}} px-4 py-3 rounded relative" role="alert" x-data="{show: true}" x-show="show">
        <p>{{ session('message') }}</p>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="show = false">
          <svg class="fill-current h-6 w-6 {{session('alert-class')}}" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
        </span>
    </div>

    @endif
    <div class="mt-8 text-2xl flex justify-between">
        <div>Mensajes @if($device_id) del dispositivo {{$device->name}} @endif  </div> 
        <div>
            <x-jet-button wire:click="render" class="bg-blue-500 hover:bg-blue-700" title="Edit">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>   
            </x-jet-button>
        </div>
        
    </div>

    
    
    <div class="mt-6">
        <div class="flex justify-between">
            <div class="">
                <input wire:model.debounce.500ms="q" type="search" placeholder="Search" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            {{-- <div class="mr-2">
                <input type="checkbox" class="mr-2 leading-tight" wire:model="active" />Active Only?
            </div> --}}
            <div class="">
                <select wire:model.debounce.500ms="perPage" class=" outline-none text-gray-500 text-sm " >
                    <option value="5">5 por página</option>
                    <option value="10">10 por página</option>
                    <option value="15">15 por página</option>
                    <option value="25">25 por página</option>
                    <option value="50">50 por página</option>
                    <option value="100">100 por página</option>
                </select>
            </div>
        </div>

        @if($messages->count())
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">
                            <div class="flex items-center">
                                <button wire:click="sortBy('id')">ID</button>
                                <x-sort-icon sortField="id" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </div>
                        </th>
                        <th class="px-4 py-2">
                            <div class="flex items-center">
                                <button wire:click="sortBy('device')">DEVICE</button>
                                <x-sort-icon sortField="device" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </div>
                        </th>
                        <th class="px-4 py-2">
                            <div class="flex items-center">
                                <button wire:click="sortBy('name')">NAME</button>
                                <x-sort-icon sortField="name" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </div>
                        </th>
                        <th class="px-4 py-2">
                            <div class="flex items-center">
                                <button wire:click="sortBy('data')">DATA</button>
                                <x-sort-icon sortField="data" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </div>
                        </th>
                        <th class="px-4 py-2">
                            <div class="flex items-center">
                                <button wire:click="sortBy('time')">TIME</button>
                                <x-sort-icon sortField="time" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </div>
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 ">
                    @foreach ($messages as $message)
                        <tr class="text-gray-500 ">
                            <td class="py-2 px-6">{{$message->ID}}</td>
                            <td class="py-2 px-6">{{$message->DEVICE}}</td>
                            <td class="py-2 px-6">{{$message->NAME}}</td>
                            <td class="py-2 px-6">
                                @if(procesa_datos($message) != '' )
                                @foreach (procesa_datos($message) as $item)
                                    {{$item}}<br>                                
                                @endforeach    
                                @endif                                
                            </td>
                            <td class="py-2 px-6">{{$message->TIME}}</td>
                            
                            
                        </tr>
                        
                    @endforeach

                </tbody>
            </table>

            <div class="bg-white px-4 py-3  items-center justify-between border-t border-gray-200 sm:px-6">
                {{$messages->links()}}
            </div>
        @else 
            <div class="bg-white px-4 py-3  items-center justify-between border-t border-gray-200 sm:px-6">
                No hay resultado para la busqueda {{ $q }}
            </div>
        @endif
    </div>

</div>


<script>

var myVar = setInterval(myTimer, 10000);

function myTimer() {
  document.getElementById("actualiza").click();
} 

</script>


