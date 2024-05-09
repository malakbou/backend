<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Notifications\EventCreated;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NotifyUsersAboutEvents implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $users;
    public $event;

    public function __construct($event , $users)
    {
        $this->event = $event;
        $this->users = $users;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->users as $user) {
            $user->notify(new EventCreated($this->event, $user->username));
        }
    }
}