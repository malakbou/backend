<?php

use Illuminate\Support\Facades\Broadcast;

// use Illuminate\Support\Facades\Broadcast;
// use Illuminate\Support\Facades\Redis; // Ajoutez cette ligne pour utiliser Redis

// Broadcast::channel('notifications', function ($user) {
//     // Store user id in a list when connected
//     // You can use Redis or another cache system to store user ids
//     // For example, if using Redis:
//     Redis::sadd('connected_users', $user->id); // Ajoutez l'ID de l'utilisateur à la liste
//     // Or store in a database table
// });

// Broadcast::channel('notifications', function ($user) {
//     // Remove user id from the list when disconnected
//     // For example, if using Redis:
//     Redis::srem('connected_users', $user->id); // Supprimez l'ID de l'utilisateur de la liste
// });




Broadcast::channel('notifications.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
