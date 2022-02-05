<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRequestsToJoinGroup extends Notification
{
    use Queueable;

    private $pg;
    private $user;
    
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($pg, $user)
    {
        $this->pg = $pg;
        $this->user = $user;
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
                    ->greeting(__('mail/general.greeting'))
                    ->subject(__('mail/peergroup.subject_user_wants_to_join_group', ['user_name' => $this->user->name]))
                    ->line(__('mail/peergroup.notification_user_wants_to_join_group', ['user_name' => $this->user->name, 'title' => $this->pg->title]))
                    ->action(__('mail/peergroup.button_show_request'), $this->pg->getUrl())
                    ->line(__('mail/general.thank_you_for_using'));
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
            'peergroup_id' => $this->pg->id,
            'groupname' => $this->pg->groupname,
            'url' => $this->pg->getUrl(),
            'title' => $this->pg->title,
            'user_name' => $this->user->name,
            'user_id' => $this->user->id,
        ];
    }
}
