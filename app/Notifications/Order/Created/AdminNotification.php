<?php

namespace App\Notifications\Order\Created;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminNotification extends Notification implements ShouldQueue
{
    use Queueable;
    
    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Order $order,
    ) {
        //
    }
    
    public function viaQueues(): array
    {
        return [
            'mail' => 'admin-notification',
        ];
    }
    
    public function via(User $user): array
    {
        return ['mail'];
    }
    
    /**
     * Get the mail representation of the notification.
     */
    public function toMail(User $user): MailMessage
    {
        return (new MailMessage)
            ->subject('New order №' . $user->firstname . '  ' . $user->lastname)
            ->line("Hello $user->name,")
            ->action(
                'Get invoice',
                url(route('order.invoice', $this->order->vendor_order_id))
            )
            ->line('Thank you for using our application!');
    }
}
