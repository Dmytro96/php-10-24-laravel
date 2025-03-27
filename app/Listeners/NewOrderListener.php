<?php

namespace App\Listeners;

use App\Enums\RoleEnum;
use App\Events\OrderCreatedEvent;
use App\Models\User;
use App\Notifications\Order\Created\AdminNotification;
use App\Notifications\Order\Created\UserNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NewOrderListener implements ShouldQueue
{
    public function viaQueue(): string
    {
        return 'default';
    }
    
    public function handle(OrderCreatedEvent $event): void
    {
        logs()->info("[NewOrderListener::handle] Order created with ID: {$event->order->id}");
        
        Notification::send(
            User::role(RoleEnum::ADMIN)->get(),
            app(
                AdminNotification::class,
                ['order' => $event->order],
            )
        );
        
        $event->order->notify(
            app(
                UserNotification::class,
                ['order' => $event->order],
            )
        );
    }
}
