<?php

namespace App\Http\Livewire;
use App\Models\User;
use Livewire\WithPagination;

use Livewire\Component;

class UsersComponent extends Component
{
    use WithPagination; 
    
    
    public function render()
    {

        return view('livewire.users-component', ['users' => User::paginate(5)]
    );
    }
}
