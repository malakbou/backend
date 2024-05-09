<?php

namespace App\Notifications;


use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\support\Facades\Auth;
use Illuminate\Notifications\Messages\MailMessage;

class EventCreated extends Notification
{
    use Queueable;


    public Event $event;
    public string $username;




    public function __construct(Event $event, string $username)
    {
        $this->event = $event;
        $this->username = $username;
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
     * Get the mail representation of the notification.
     */
    // public function toMail(object $nhttps://www.youtube.com/watch?v=pT6VBKMnuFgotifiable): MailMessage
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */

    // Video
    public function toDatabase(object $notifiable)
     {
        return[ 
            'event_id' => $this->event->id,
            'title' => $this->event->title,
            'description' => $this->event->description,
            'start_time' => date('Y-m-d', strtotime($this->event->start_time)),
            'end_time' => date('Y-m-d', strtotime($this->event->end_time)),
            'typeevent' => $this->event->typeevent,
            'user' => $this->username,
        ];
    }


    // public function toArray(object $notifiable): array
    // {
    //     return [
    //         'event' => $this->event
    //     ];
    // }
}
