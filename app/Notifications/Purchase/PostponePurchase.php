<?php

namespace App\Notifications\Purchase;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Lang;

class PostponePurchase extends Notification
{
    use Queueable;

    private $purchaseId;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($purchaseId)
    {
        $this->purchaseId = $purchaseId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject(Lang::get('Purchase') . ' ' . Lang::get('Postponed'))
                    ->line(Lang::get('The purchase you submitted was postponed.'))
                    ->line(Lang::get('Please click the button below to view the purchase details.'))
                    ->action(Lang::get('View') . ' ' . Lang::get('Purchase'), route('purchase.show', $this->purchaseId));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
