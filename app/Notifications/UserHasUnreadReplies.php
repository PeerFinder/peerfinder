<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Talk\Models\Receipt;
use Illuminate\Support\Str;

class UserHasUnreadReplies extends Notification
{
    use Queueable;

    private $receipt;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Receipt $receipt)
    {
        $this->receipt = $receipt;
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
                ->greeting(__('mail/general.greeting'))
                ->subject(__('mail/talk.unread_messages_conversation', ['conversation_title' => Str::limit($this->receipt->conversation->getTitle(), 50)]))
                ->line(__('mail/talk.unread_messages_conversation', ['conversation_title' => $this->receipt->conversation->getTitle()]))
                ->action(__('mail/talk.button_show_conversation'), route('talk.show', ['conversation' => $this->receipt->conversation->identifier]))
                ->line(__('mail/general.thank_you_for_using'));
    }
}
