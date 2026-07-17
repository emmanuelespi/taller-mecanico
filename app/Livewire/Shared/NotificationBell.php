<?php

namespace App\Livewire\Shared;

use Livewire\Component;

class NotificationBell extends Component
{
    protected $listeners = [
        'orderSaved' => '$refresh',
        'refreshNotifications' => '$refresh',
        'notify' => '$refresh',
    ];

    public function getNotificationsProperty()
    {
        return auth()->check() 
            ? auth()->user()->unreadNotifications()->take(10)->get() 
            : collect();
    }

    public function getUnreadCountProperty()
    {
        return auth()->check() 
            ? auth()->user()->unreadNotifications()->count() 
            : 0;
    }

    public function markAsRead($notificationId)
    {
        if (!auth()->check()) return;

        $notification = auth()->user()->unreadNotifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }

        $this->dispatch('refreshNotifications');
    }

    public function markAllAsRead()
    {
        if (!auth()->check()) return;

        auth()->user()->unreadNotifications->markAsRead();
        $this->dispatch('refreshNotifications');
    }

    public function render()
    {
        return view('livewire.shared.notification-bell');
    }
}
