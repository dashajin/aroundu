<?php

namespace App\Notifications;

use App\User;
use App\Vote;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class GotVote extends Notification
{
    use Queueable;

    protected $vote;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Vote $vote)
    {
        $this->vote = $vote;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
        $user = $this->vote->user;
        $votable = $this->vote->votable;
        return [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_avatar' => $user->avatar,
            'votable_id' => $votable->id,
            'votable_title' => $votable->title,
        ];
    }
}
