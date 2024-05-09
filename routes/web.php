<?php

use App\Models\User;
use App\Models\Event;
use App\Notifications\EventCreated;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/notify', function () {
    // User::factory()->create();  // Creer fake user 
    // User::factory()->create();

    // Récupère le premier utilisateur dans la base de données
    $user = User::find(1);

    // Récupère les notifications de l'utilisateur
    $notifications = $user->notifications;

    // Affiche le contenu des notifications (utile pour le débogage)
    dd($notifications);

    // $event = Event::find(2);  // Recuperer le event num 2
    // $user->notify(new EventCreated($event)); // Notifier l'utilisateur connecté
});

Route::get('/pusher', function () {
    return view('pusher');
});



