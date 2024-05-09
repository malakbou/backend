<?php


namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Notifications\EventCreated;
use App\Http\Controllers\Controller;
use App\Jobs\NotifyUsersAboutEvents;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Validator;
use App\Notifications\UpdatedEventNotification;




class EventControler extends Controller
{


    /**************** SHOW Calendar BY Type Event *************/
    public function searchEventByType($typeevent)
    {
        // Initialiser une variable pour stocker les événements
        $events = null;

        // Traiter chaque type d'événement
        switch ($typeevent) {
            case 'Online_Meetings':
                $events = Event::where('typeevent', 'Online_Meetings')->get();
                break;
            case 'Project':
                $events = Event::where('typeevent', 'Project')->get();
                break;
            case 'Vacations':
                $events = Event::where('typeevent', 'Vacations')->get();
                break;
            case 'In_person_Meetings':
                $events = Event::where('typeevent', 'In_person_Meetings')->get();
                break;
            default:
                // Retourner une liste vide si le type d'événement n'existe pas
                return response()->json([
                    'status' => 200,
                    'message' => 'Aucun événement trouvé pour ce type',
                    'events' => []
                ], 200);
        }

        // Vérifier si des événements ont été trouvés
        if ($events->count() > 0) {
            return response()->json([
                'status' => 200,
                'message' => 'Événements trouvés',
                'data' => $events,
            ], 200);
        } else {
            // Retourner un message si aucun événement n'a été trouvé pour ce type
            return response()->json([
                'status' => 404,
                'message' => 'Aucun événement trouvé pour ce type',
            ], 404);
        }
    }



    /**************************** INDEX *****************************/
    public function index()
    {
        $event = Event::all();
        if ($event->count() > 0) {
            return Response::json([
                'status' => 200,
                'event' => $event
            ], 200);
        } else {
            return Response::json([
                'status' => 404,
                'event' => 'No records found'
            ], 404);
        }
    }

    /**************************** DELETE EVENT **************************/
    public function destroy($id)
    {

        $event = Event::find($id);

        if ($event) {
            $event->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Event Deleted Sucessfully'
            ], 200);

        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Event Not Exist'
            ], 404);

        }
    }


    /**************************** STORE EVENT ***************************/

    public function store(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|string|max:191',
                'description' => 'required|string|max:500',
                'start_time' => 'required|date',
                'end_time' => 'required|date',
                'typeevent' => 'required|string|in:Online_Meetings,Project,Vacations,In_person_Meetings',
            ]
        );

        if ($validator->fails()) {

            return response()->json([
                'status' => 422,
                'ERRORS' => $validator->messages()
            ], 422);

        } else {

            $event = Event::create([
                'title' => $request->title,
                'description' => $request->description,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'typeevent' => $request->typeevent,
            ]);


            // Envoyer la notification à chaque utilisateur
            $users = User::where('privileges', '<>', 'client')->get();
            NotifyUsersAboutEvents::dispatch($event,$users);
        }

    }

    /*************************** UPDATE EVENT***************************/

    public function update(Request $request, int $id)
    {

        $validated = $request->validate([
            'title' => 'required|string|max:191',
            'description' => 'required|string|max:500',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'typeevent' => 'required|string|in:Online_Meetings,Project,Vacations,In_person_Meetings',
        ]);

        
        $event = Event::findOrFail($id);
        $event->update($validated);


        $users = User::all();
        foreach ($users as $user) {
            $user->notify(new UpdatedEventNotification($event));
        }

        return response('success', 200);
    }


}

