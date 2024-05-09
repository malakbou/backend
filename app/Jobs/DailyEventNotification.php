<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Event;
use App\Notifications\EventReminder;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DailyEventNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $events =  Event::whereBetween('start_time',[today()->addDay(),today()->addDays(2)])->get();

        $users = User::where('privileges', '<>', 'client')->get();
        foreach ($events as $event) {
            foreach ($users as $user) {
                $user->notify(new EventReminder($event, $user->username));
            }
        }
    }
}
