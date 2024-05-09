<?php

namespace App\Notifications;

use App\Models\Tache;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TacheCreated extends Notification
{
    use Queueable;
    public Tache $tache;
    /**
     * Create a new notification instance.
     */
    public function __construct(Tache $tache)
    {
        $this->tache = $tache ;
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
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    // public function toArray(object $notifiable): array
    // {
    //     return [
    //         'tache' => $this->tache
    //     ];
    // }


    public function toDatabase(object $notifiable)
    {
       return[ 
           'tache_id' => $this->tache->id,
           'title' => $this->tache->nom,
           'description' => $this->tache->description,
           'typeevent' => $this->tache->priorite,
           'end_time' => Carbon::createFromFormat('Y-m-d', $this->tache->datefin),
        //    'end_time' => Carbon::createFromFormat('Y-m-d H:i:s', $this->tache->datefin)->format('Y-m-d'),
           //'end_time' => Carbon::createFromFormat('Y-m-d',$this->tache->datefin),

       ];
   }

   


    
}
