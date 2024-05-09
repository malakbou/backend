<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationCountController extends Controller
{
  
    public function __invoke(Request $request)
    {
        // Compter nb notification unread pour le user authentifier
        return response()->json(auth()->user()->unreadNotifications()->count());
    }
}
