


<div class="p-6 sm:px-20 bg-white border-b border-gray-200">
    @if(session()->has('message'))
    <div class="flex items-center bg-blue-500 text-white text-sm font-bold px-4 py-3 relative" role="alert" x-data="{show: true}" x-show="show">
        <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z"/></svg>
        <p>{{ session('message') }}</p>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="show = false">
            <svg class="fill-current h-6 w-6 text-white" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
        </span>
    </div>
    @endif
    <div class="mt-8 text-2xl flex justify-between">
        <div>Devices</div> 
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
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($devices as $device)
                        <tr>
                            <td class="border px-4 py-2">{{ $device->id}}</td>
                            <td class="border px-4 py-2">
                                <x-jet-nav-link href="{{ route('messages.show',$device ) }}" :active="request()->routeIs('messages')">
                                    {{ $device->device}}
                                </x-jet-nav-link>
                            </td>
                            <td class="border px-4 py-2">{{ $device->name}}</td>
                            <td class="border px-4 py-2">{{ $device->description}}</td>
                            <td class="border px-4 py-2">{{ $device->user}}</td>
                        
                            <td class="border px-4 py-2">
                                <x-jet-button wire:click="confirmItemEdit( {{ $device->id}})" class="bg-blue-500 hover:bg-blue-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </x-jet-button>
                                <x-jet-danger-button wire:click="confirmItemDeletion( {{ $device->id}})" wire:loading.attr="disabled">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                        <path fillRule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clipRule="evenodd" />
                                    </svg>
                                </x-jet-danger-button>

                                <x-jet-danger-button  class="bg-green-500 hover:bg-green-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
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
                <x-jet-input id="user" type="text" class="form-control " wire:model.defer="user" />
                <x-jet-input-error for="user" class="mt-2" />
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
 
    
</div>
