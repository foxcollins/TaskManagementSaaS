<?php

namespace App\Livewire;

use Livewire\Component;

class Logout extends Component
{
    public function mount()
    {
        session()->flush();
        return redirect('/');
    }
    public function render()
    {
        return view('livewire.logout');
    }
}
