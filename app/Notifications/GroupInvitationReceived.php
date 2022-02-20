<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GroupInvitationReceived extends Notification
{
    use Queueable;

    private $pg;
    private $receiver;
    private $sender;
    private $invitation;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($pg, $receiver, $sender, $invitation)
    {
        $this->pg = $pg;
        $this->receiver = $receiver;
        $this->sender = $sender;
        $this->invitation = $invitation;
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
                ->subject(__('mail/peergroup.subject_group_invitation_received', ['title' => $this->pg->title]))
                ->line(__('mail/peergroup.notification_group_invitation_received', ['user_name' => $this->sender->name, 'title' => $this->pg->title]))
                ->line(__('mail/peergroup.notification_group_invitation_comment', ['comment' => $this->invitation->comment]))
                ->action(__('mail/peergroup.button_show_group'), $this->pg->getUrl())
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
            'user_name' => $this->sender->name,
            'user_id' => $this->sender->id,
        ];
    }
}
