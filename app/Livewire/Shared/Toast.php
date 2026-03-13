<?php

namespace App\Livewire\Shared;

use Livewire\Component;

class Toast extends Component
{
    public bool $show = false;

    public string $message = '';

    public string $type = 'success';

    protected $listeners = [
        'notify' => 'showNotification',
    ];

    public function showNotification(string $message, string $type = 'success'): void
    {
        $this->message = $message;
        $this->type = $type;
        $this->show = true;
    }

    public function hide(): void
    {
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.shared.toast');
    }
}
