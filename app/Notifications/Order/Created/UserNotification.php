<?php

namespace App\Notifications\Order\Created;

use App\Models\Order;
use App\Services\Contracts\InvoiceServiceContract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserNotification extends Notification implements ShouldQueue
{
    use Queueable;
    
    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Order $order,
        protected readonly InvoiceServiceContract $invoiceService,
    )
    {
        logs()->info('[UserNotification] constructor');
    }
    
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }
    
    /**
     * Get the mail representation of the notification.
     */
    public function toMail(Order $order): MailMessage
    {
        return (new MailMessage)
            ->subject('Your order created ' . $order->id)
            ->line('Hello, ' . $order->firstname . ' ' . $order->lastname . '!')
            ->action('Order info', url(route('order.thank-you', $order->vendor_order_id)))
            ->line('Thank you for using our application!')
            ->attachData($this->invoiceService->generate($order)->output, 'test_invoice.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
