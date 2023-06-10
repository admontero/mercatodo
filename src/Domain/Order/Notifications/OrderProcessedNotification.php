<?php

namespace Domain\Order\Notifications;

use Domain\Order\Models\Order;
use Domain\Order\States\Canceled;
use Domain\Order\States\Completed;
use Domain\Order\States\Pending;
use Domain\User\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderProcessedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Order $order
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(User $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(User $notifiable): MailMessage
    {
        $state = match ($this->order->state->getValue()) {
            Pending::class => 'created',
            Completed::class => 'paid',
            Canceled::class => 'canceled',
            default => '',
        };

        return (new MailMessage())
                    ->subject('Order ' . $state)
                    ->line('Hi' . ' ' . $notifiable->profileable?->first_name . ' ' . $notifiable->profileable?->last_name)
                    ->line('Your Order #' . $this->order->code . ' has been ' . $state . '.')
                    ->line('Thank you for using our application!');
    }
}
