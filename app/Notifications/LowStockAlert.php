<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockAlert extends Notification
{
    use Queueable;

    protected $item;

    /**
     * Create a new notification instance.
     */
    public function __construct($item)
    {
        $this->item = $item;
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
            'item_id'   => $this->item->id,
            'item_name' => $this->item->name,
            'current_stock' => $this->item->stock,
            'min_stock' => $this->item->minimum_stock,
            'message'   => "Peringatan! Stok {$this->item->name} menipis ({$this->item->stock} {$this->item->unit}). Segera lakukan pengadaan.",
            'type'      => 'low_stock',
            'icon'      => 'fas fa-exclamation-triangle',
            'color'     => 'text-warning'
        ];
    }
}
