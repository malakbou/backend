<?php
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmployeC;
use App\Http\Controllers\Api\ProjectC;
use App\Http\Controllers\Api\EventControler;
use App\Http\Controllers\Api\userController;
use App\Http\Controllers\Api\TacheController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\realtimeNotification;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\departementController;
use App\Http\Controllers\Api\NotificationCountController;

// Sanctum::actingAs(11);


/***************************** USERS ******************************/

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/auth/logout', [userController::class, 'logout']);
    Route::get('/user/Notifications', [NotificationController::class, 'getNotifications']);
    Route::put('/Notifications/{notificationId}', [NotificationController::class, 'markAsRead']);
    /*************************** realtimeNotifications ******************************/
    Route::get('/realtimeNotifications', realtimeNotification::class);

    /*************************** CountsNotifications ******************************/
    Route::get('/unread-notifications-count', NotificationCountController::class);


    /*************************** Profile Picture ******************************/
    Route::post('/uploadProfilePicture', [userController::class, 'uploadProfilePicture'])->name('image.store');
    Route::put('/updateProfilePicture', [userController::class, 'updateProfilePicture']);
    Route::delete('/deleteProfilePicture', [userController::class, 'deleteProfilePicture']);  


    /*************************** Change Password******************************/
    Route::post('changePassword/{id}', [userController::class, 'changePassword']);
});


// Route::post('/auth/login', [ProjectC::class, 'login']);
Route::post('/auth/login', [ProjectC::class, 'login']);
Route::get('/auth/index', [ProjectC::class, 'index']);




//project
Route::put('/project/update/{id}', [ProjectC::class, 'update']);
Route::post('/project/search', [ProjectC::class, 'search']);
Route::post('/project/store', [ProjectC::class, 'store']);
Route::get('/project/show/{id}', [ProjectC::class, 'show']);
Route::get('/project/index', [ProjectC::class, 'index']);

//tache
Route::post('/tache/store/{id}', [TacheController::class, 'store']);
Route::put('/tache/update/{id}', [TacheController::class, 'update']);
Route::post('/tache/updatestatus/{id}', [TacheController::class, 'updatestatus']);
Route::delete('/tache/delete/{id}', [TacheController::class, 'delete']);
Route::get('/tache/index', [TacheController::class, 'index']);
Route::get('/tache/show/{id}', [TacheController::class, 'show']);
Route::get('/tache/showproject/{id}', [TacheController::class, 'showproject']);
Route::get('/tache/showemploye/{id}', [TacheController::class, 'showemploye']);


//client
Route::put('/client/update/{id}', [ClientController::class, 'update']);
Route::post('/client/search', [ClientController::class, 'search']);
Route::get('/client/index', [ClientController::class, 'index']);
Route::get('/client/show/{id}', [ClientController::class, 'show']);





/*************************** DEPARTEMENT ******************************/
Route::get('departements', [departementController::class, 'index']);
Route::post('departements', [departementController::class, 'store']);
Route::delete('departements/delete/{id}', [departementController::class, 'destroy']);
Route::put('departements/{id}', [departementController::class, 'update']);
Route::get('departements/{id}', [departementController::class, 'search']);

/*************************** Employe ******************************/
Route::get('employes', [EmployeC::class, 'index']);
Route::post('employes', [EmployeC::class, 'store']);
Route::delete('employes/{id}', [EmployeC::class, 'destroy']);
Route::put('employes/{id}', [EmployeC::class, 'update']);
Route::get('employes/{id}', [EmployeC::class, 'show']);
Route::post('employe/search', [EmployeC::class, 'search']);
Route::get('employe/indexchef', [EmployeC::class, 'indexchef']);



/*************************** EVENT ******************************/

Route::get('events', [EventControler::class, 'index']);
Route::post('events', [EventControler::class, 'store']);
Route::delete('events/{id}', [EventControler::class, 'destroy']);
Route::put('eventes/{id}', [EventControler::class, 'update']);
Route::get('events/searchEventByType/{typeevent}', [EventControler::class, 'searchEventByType']);


