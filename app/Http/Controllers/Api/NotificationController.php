<?php


namespace App\Http\Controllers\Api;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\NotificationResource;


  class NotificationController extends Controller
    {

    /*********************** get user notifications *******************/
        public function getNotifications(Request $request)
        {  
            // dd(auth()->user());
            return NotificationResource::collection(auth()->user()->notifications)->collection;
        }




    //************* Mark a specific notification as read  **************/
    public function markAsRead($notificationId)
    {
        $user = Auth::user();

        // Find the notification by ID that belongs to the authenticated user
        $notification = $user->notifications()->find($notificationId);

        if ($notification) {
            // Mark the notification as read
            $notification->markAsRead();

            return response()->json(['message' => 'Notification marked as read'], 200);
        }

        return response()->json(['error' => 'Notification not found'], 404);
    }
    }

