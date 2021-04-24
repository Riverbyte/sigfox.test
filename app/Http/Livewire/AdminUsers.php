<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;

use Livewire\WithPagination;

class AdminUsers extends Component
{
    use WithPagination;

    public $active;
    public $q;
    public $sortBy = 'id';
    public $sortAsc = true;
    public $item;
    public $perPage = '10';

    protected $paginationTheme = 'bootstrap';

    public $search;

    public function render()
    {
        $users = User::where('name','LIKE','%' . $this->search . '%')
                ->orwhere('email','LIKE','%' . $this->search . '%')
                ->orderBy( $this->sortBy, $this->sortAsc ? 'ASC' : 'DESC')
                ->paginate(8);

        return view('livewire.admin-users', compact('users'));
    }


    public function limpiar_page()
    {
        $this->reset('page');
    }

    public function sortBy( $field) 
    {
        if( $field == $this->sortBy) {
            $this->sortAsc = !$this->sortAsc;
        }
        $this->sortBy = $field;
    }

}
