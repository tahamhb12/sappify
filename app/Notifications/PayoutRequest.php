<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PayoutRequest extends Notification
{
    use Queueable;

    public $partner_id;
    public $program_id;


    /**
     * Create a new notification instance.
     */
    public function __construct($partner_id,$program_id)
    {
        $this->partner_id = $partner_id;
        $this->program_id = $program_id;

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
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('New Payout Request.')
                    ->action('View Payouts', url("/admin/$this->partner_id/affiliate-programs/$this->program_id?activeRelationManager=2"))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
