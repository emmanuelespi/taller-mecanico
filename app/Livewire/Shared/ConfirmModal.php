<?php

namespace App\Livewire\Shared;

use Livewire\Component;

class ConfirmModal extends Component
{
    public bool $open = false;

    public string $title = '¿Estás seguro?';

    public string $message = 'Esta acción no se puede deshacer.';

    public string $confirmEvent = '';

    public mixed $confirmParams = null;

    protected $listerners = [
        'confirmeDelete' => 'openModal',
    ];

    public function openModal(string $event, mixed $params = null, string $title = '¿Estás seguro?', string $message = 'Esta acción no se puede deshacer.'): void
    {
        $this->confirmEvent = $event;
        $this->confirmParams = $params;
        $this->title = $title;
        $this->message = $message;
        $this->open = true;
    }

    public function confirm(): void
    {
        $this->dispatch($this->confirmEvent, $this->confirmParams);
        $this->close();
    }

    public function close(): void
    {
        $this->open = false;
        $this->reset = (['title', 'message', 'confirmEvent', 'confirmParams']);
    }

    public function render()
    {
        return view('livewire.shared.confirm-modal');
    }
}
