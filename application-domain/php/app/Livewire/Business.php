<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Business')]
class Terms extends Component
{
    public function render()
    {
        $title = 'Business';
        $company_name = app_name();
        $app_email = setting('email');

        return view('livewire.business', compact('title', 'company_name', 'app_email'));
    }
}
