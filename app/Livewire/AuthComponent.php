<?php

namespace App\Livewire;

use Livewire\Component;

class AuthComponent extends Component
{

    public $view = "register";


    public function changeView()
    {
        $this->view = $this->view === 'register' ? 'login' : 'register';
    }

    public function render()
    {
        return view('livewire.auth-component');
    }
}
