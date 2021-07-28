<?php

namespace App\Notifications;

use App\Models\Federation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FederationStatusChanged extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Federation $federation)
    {
        $this->federation = $federation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
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
                    ->subject(__('notifications.federation_status_changed_subject'))
                    ->line(__('notifications.federation_status_changed_body', [
                        'name' => $this->federation->name,
                        'status' => $this->federation->active ? strtolower(__('common.active')) : strtolower(__('common.inactive')),
                    ]))
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
            'body' => __('notifications.federation_status_changed_body', [
                'name' => $this->federation->name,
                'status' => $this->federation->active ? strtolower(__('common.active')) : strtolower(__('common.inactive')),
            ]),
            'federation_id' => $this->federation->id,
        ];
    }
}
