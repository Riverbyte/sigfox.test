


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
        <div>Dispositivos 
        
        <x-jet-button wire:click="render" class="bg-blue-500 hover:bg-blue-700" title="Actualizar">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-0 my-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
              </svg>
        </x-jet-button>
                
        </div> 
        
        <div class="mr-2">
            <x-jet-button wire:click="confirmItemAdd" class="bg-blue-500 hover:bg-blue-700">
                Add New Item
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

        @if($devices->count())
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
                                <button wire:click="sortBy('description')">DESCRIPTION</button>
                                <x-sort-icon sortField="description" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </div>
                        </th>
                        <th class="px-4 py-2">
                            <div class="flex items-center">
                                <button wire:click="sortBy('user')">USER</button>
                                <x-sort-icon sortField="user" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                            </div>
                        </th>
                        <th class="px-4 py-2">
                            <div class="flex items-center">
                                STATUS
                            </div>
                        </th>
                        <th class="px-4 py-2">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($devices as $device)
                        <tr>
                            <td class="border px-4 py-2">{{ $device->id}}</td>
                            <td class="border px-4 py-2">

                                @if (request()->routeIs('devices'))
                                    <x-jet-nav-link href="{{ route('messages.show',$device ) }}" :active="request()->routeIs('messages')">
                                        {{ $device->device}}
                                    </x-jet-nav-link>  
                                @else
                                    <x-jet-nav-link href="{{ route('admin.messages.show',$device ) }}" :active="request()->routeIs('messages')">
                                        {{ $device->device}}
                                    </x-jet-nav-link>  
                                @endif
                                
                            </td>
                            <td class="border px-4 py-2">{{ $device->name}}</td>
                            <td class="border px-4 py-2">{{ $device->description}}</td>
                            <td class="border px-4 py-2">{{ $device->user}}</td>
                            <td class="border px-4 py-2">{{$status[$device->device]}} </td>
                        
                            <td class="border px-4 py-2">
                                {{-- https://heroicons.com/ --}}
                                <x-jet-danger-button wire:click="confirmEventEdit( {{ $device->id}})"  class="bg-green-500 hover:bg-green-700"   title="Configure">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 ">
                                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                    </svg>
                                </x-jet-danger-button>
                                

                                <x-jet-button wire:click="confirmItemEdit( {{ $device->id}})" class="bg-blue-500 hover:bg-blue-700" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                    
                                </x-jet-button>

                                @if($status[$device->device] == 'SUSPENDED')
                                    <x-jet-button wire:click="enableItem( {{$device}})" class=" bg-green-500 hover:bg-green-700" title="Resume">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        
                                    </x-jet-button>    
                                @else
                                    <x-jet-button wire:click="confirmItemSuspend( {{ $device->id}})" class=" bg-yellow-700 hover:bg-yellow-900" title="Suspend">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                        <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd" />
                                        </svg>
                                        
                                    </x-jet-button>  
                                @endif
                                

                                <x-jet-danger-button wire:click="confirmItemDeletion( {{ $device->id}})" class="" wire:loading.attr="disabled" title="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                        <path fillRule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clipRule="evenodd" />
                                    </svg>
                                    
                                </x-jet-danger-button>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="bg-white px-4 py-3  items-center justify-between border-t border-gray-200 sm:px-6">
                {{ $devices->links() }}
            </div>
        @else 
            <div class="bg-white px-4 py-3  items-center justify-between border-t border-gray-200 sm:px-6">
                No hay resultado para la busqueda {{ $q }}
            </div>
        @endif
    </div>
        




    <x-jet-confirmation-modal wire:model="confirmingItemSuspend">
        <x-slot name="title">
            {{ __('Suspend Item') }}
        </x-slot>
 
        <x-slot name="content">
            {{ __('Are you sure you want to suspend Item? ') }}
        </x-slot>
 
        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$set('confirmingItemSuspend', false)" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>
 
            <x-jet-danger-button class="ml-2" wire:click="suspendItem({{ $confirmingItemSuspend }})" wire:loading.attr="disabled">
                {{ __('Suspend') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>





    <x-jet-confirmation-modal wire:model="confirmingItemDeletion">
        <x-slot name="title">
            {{ __('Delete Item') }}
        </x-slot>
 
        <x-slot name="content">
            {{ __('Are you sure you want to delete Item? ') }}
        </x-slot>
 
        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$set('confirmingItemDeletion', false)" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>
 
            <x-jet-danger-button class="ml-2" wire:click="destroy({{ $confirmingItemDeletion }})" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>



    <x-jet-dialog-modal wire:model="confirmingItemAdd">
        <x-slot name="title">
            {{ isset( $this->device_id) ? 'Edit Device' : 'Add Device'}}
        </x-slot>
 
        <x-slot name="content">
            <div class="mb-4">
                <x-jet-label for="device" class="form-label mb-2" value="{{ __('Device') }}" />
                <x-jet-input id="device" type="text" class="form-control " wire:model.defer="device" />
                <x-jet-input-error for="device" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-jet-label for="name" class="form-label mb-2" value="{{ __('Name') }}" />
                <x-jet-input id="name" type="text" class="form-control " wire:model.defer="name" />
                <x-jet-input-error for="name" class="mt-2" />
            </div>
 
            <div class="mb-4">
                <x-jet-label for="description" class="form-label mb-2" value="{{ __('Description') }}" />
                <x-jet-input id="description" type="text" class="form-control " wire:model.defer="description" />
                <x-jet-input-error for="description" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-jet-label for="user" class="form-label mb-2" value="{{ __('User') }}" />
                <x-jet-input id="user" type="text" class="form-control " wire:model.defer="user"  />
                <x-jet-input-error for="user" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-jet-label for="pac" class="form-label mb-2" value="{{ __('Pac') }}" />
                <x-jet-input id="pac" type="text" class="form-control " wire:model.defer="pac" />
                <x-jet-input-error for="pac" class="mt-2" />
            </div>
 
            {{-- <div class="col-span-6 sm:col-span-4 mt-4">
                <label class="flex items-center">
                    <input type="checkbox" wire:model.defer="item.status" class="form-checkbox" />
                    <span class="ml-2 text-sm text-gray-600">Active</span>
                </label>
            </div> --}}
        </x-slot>
 
        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$set('confirmingItemAdd', false)" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>
 
            <x-jet-danger-button class="ml-2" wire:click="saveItem()" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>



    <x-jet-dialog-modal wire:model="confirmingEventAdd">
        <x-slot name="title">
            {{ isset( $this->device_id) ? 'Editar dispositivo' : 'Add Device'}}
        </x-slot>
 
        <x-slot name="content">

            <div class="card mb-2">
                Doy mi consentimiento de recibir llamadas telefónicas, mensajes de texto y/o correos electrónicos de las notificaciones de alerta de este dispositivo. 
            </div>

            <h1 class="text-2x1 font-bold">Email: <input type="checkbox" class="form-checkbox rowCheckbox focus:outline-none focus:shadow-outline" wire:model.defer="email_checkbox"></h1>

            <div class="mb-4">
                <x-jet-label for="email_destination" class="form-label mb-2" value="{{ __('Email adress') }}" />
                <x-jet-input id="email_destination" type="text" class="form-control " wire:model.defer="email_destination" />
                <x-jet-input-error for="email_destination" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-jet-label for="email_content" class="form-label mb-2" value="{{ __('Message') }}" />
                <x-jet-input id="email_content" type="text" class="form-control " wire:model.defer="email_content" />
                <x-jet-input-error for="email_content" class="mt-2" />
            </div>

            <hr>

            <h1 class="text-2x1 font-bold">Message: <input type="checkbox" class="form-checkbox rowCheckbox focus:outline-none focus:shadow-outline" wire:model.defer="message_checkbox"></h1>

            <div class="mb-4">
                <x-jet-label for="message_destination" class="form-label mb-2" value="{{ __('Phone number') }}" />
                <x-jet-input id="message_destination" type="text" class="form-control " wire:model.defer="message_destination" />
                <x-jet-input-error for="message_destination" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-jet-label for="message_content" class="form-label mb-2" value="{{ __('Message') }}" />
                <x-jet-input id="message_content" type="text" class="form-control " wire:model.defer="message_content" />
                <x-jet-input-error for="message_content" class="mt-2" />
            </div>

            <hr>

            <h1 class="text-2x1 font-bold">Call: <input type="checkbox" class="form-checkbox rowCheckbox focus:outline-none focus:shadow-outline" wire:model.defer="call_checkbox"></h1>

            <div class="mb-4">
                <x-jet-label for="call_destination" class="form-label mb-2" value="{{ __('Phone number') }}" />
                <x-jet-input id="call_destination" type="text" class="form-control " wire:model.defer="call_destination" />
                <x-jet-input-error for="call_destination" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-jet-label for="call_content" class="form-label mb-2" value="{{ __('Message') }}" />
                <x-jet-input id="call_content" type="text" class="form-control " wire:model.defer="call_content" />
                <x-jet-input-error for="call_content" class="mt-2" />
            </div>




 
           
        </x-slot>
 
        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$set('confirmingEventAdd', false)" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>
 
            <x-jet-danger-button class="ml-2" wire:click="saveEvent()" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>
 
    
</div>
