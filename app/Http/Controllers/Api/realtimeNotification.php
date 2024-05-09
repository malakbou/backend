<?php


namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;



class realtimeNotification extends Controller
{
    public function __invoke(Request $request)
    {
        /**
         * @var User $user
         */
        $user = auth()->user();

        $start_time = now();

        $notification_count = $user->unreadNotifications()->where('created_at', '>=', $start_time)->count();

        while (! $notification_count && $start_time->diffInSeconds(now()) < 8) {
            
            info(! $notification_count && $start_time->diffInSeconds(now()) < 8);
            sleep(3);
            $notification_count = $user->unreadNotifications()->where('created_at', '>=', $start_time)->count();
        }
        return response()->json([
            'new_notification_count' => $notification_count,
            'notification_count' => $user->unreadNotifications()->count()
        ]);

    }
}
