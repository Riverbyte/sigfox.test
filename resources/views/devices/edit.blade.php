<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Items') }}
        </h2>
    </x-slot>
 

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">


                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    @if(session()->has('message'))
                    <div class=" border {{session('alert-class')}} px-4 py-3 rounded relative" role="alert" x-data="{show: true}" x-show="show">
                        <p>{{ session('message') }}</p>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="show = false">
                          <svg class="fill-current h-6 w-6 {{session('alert-class')}}" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                        </span>
                    </div>
                
                    @endif

                    <div class="mt-8 text-2xl flex justify-between">
                        <div>Asignar evento</div> 
                    </div>
                    
                    

                    <div class="card mt-5">
                        <div class="card-body">
                            <h1 class="h5">Device:</h1>
                            <p class="form-control">{{$device->name}} </p>

                            <h1 class="h5">Eventos</h1>


                            {!! Form::model($device, ['route' => ['devices.update',$device], 'method' => 'put']) !!}

                                <div class="form-group">
                                    {!! Form::label('name', 'Device') !!}
                                    {!! Form::text('name', null, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Escriba un nombre']) !!}
                                    @error('name')
                                        <span class="invalid-feedback">
                                            <strong>{{$message}}</strong>    
                                        </span>                        
                                    @enderror
                                </div>

                                
                                    <div class="form-group">
                                        {!! Form::label('name', 'Name') !!}
                                        {!! Form::text('name', $device->events->name, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Escriba un nombre']) !!}
                                        @error('name')
                                            <span class="invalid-feedback">
                                                <strong>{{$message}}</strong>    
                                            </span>                        
                                        @enderror
                                    </div>  
                            

                                {!! Form::submit('Actualizar', ['class' => 'btn btn-primary mt-2']) !!}

                            {!! Form::close() !!}



                        </div>
                    </div>
                        
                        
                    


                </div>


               
            </div>
        </div>
    </div>
</x-app-layout>