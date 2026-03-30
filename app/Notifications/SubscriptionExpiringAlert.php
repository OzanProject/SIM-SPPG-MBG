<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionExpiringAlert extends Notification
{
    use Queueable;

    protected $daysRemaining;

    /**
     * Create a new notification instance.
     */
    public function __construct($daysRemaining)
    {
        $this->daysRemaining = $daysRemaining;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'days_remaining' => $this->daysRemaining,
            'message' => "Peringatan! Masa langganan Anda akan berakhir dalam {$this->daysRemaining} hari. Segera lakukan perpanjangan agar layanan tidak terhenti.",
            'type'    => 'subscription_alert',
            'icon'    => 'fas fa-calendar-times',
            'color'   => 'text-danger'
        ];
    }
}
