<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Event;
use App\Models\Client;

use App\Models\Employe;
use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Routing\Controller;
use App\Jobs\NotifyUsersAboutEvents;
use Illuminate\support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\support\Facades\Validator;

class ProjectC extends Controller
{
    public function index()
    {
        $project = Project::all();
        return response()->json([
            'status' => 200,
            'projets' => $project,
        ], 200);
    }
    public function store(Request $request)
    {


        $validator = Validator::make(
            $request->all(),
            [
                'nom' => 'required|unique:projects|string|max:191',
                'description' => 'required|string|max:300',
                'livrable' => 'required|string|max:191',
                'objectif' => 'required|string|max:191',
                'deadline' => 'required|date',
                'retard' => 'required|boolean',
                'nom_client' => 'required|unique:clients,nom',
                'fonction' => 'required',
                'num_telf' => 'required|unique:clients',
                'mail' => 'required|email',
                'chef_id' => 'required|exists:employes,id',

            ]
        );

        if ($validator->fails()) {

            return response()->json([
                'status' => 422,
                'ERRORS' => $validator->messages(),
                'request' => $request
            ], 422);

        } else {
            /**************************************creation de user*****************************************/
            $word = $request->get('nom_client') . Str::random(5);
            $user = User::create([
                'username' => $request->get('nom_client') . 'whitelineservices',
                'password' => Hash::make($word),
                'privileges' => "client"
            ]);
            $token = $user->createToken('API Token');

            if (!$user) {
                return response()->json([
                    'statuss' => 404,
                    'message' => 'user creation failed ',
                    'password' => $word
                ], 404);
            }
            /***************************************creation de client*****************************************/

            $client = $user->clients()->create([
                'nom' => $request->get('nom_client'),
                'fonction' => $request->get('fonction'),
                'num_telf' => $request->get('num_telf'),
                'mail' => $request->get('mail'),

            ]);

            /***************************************creation de projet*****************************************/
            $project = $client->projects()->create([
                'nom' => $request->get('nom'),
                'description' => $request->get('description'),
                'livrable' => $request->get('livrable'),
                'objectif' => $request->get('objectif'),
                'deadline' => $request->get('deadline'),
                'retard' => $request->get('retard')

            ]);

            /***************************************creation de event*****************************************/
            $startTime = Carbon::now();
            $event = Event::create([
                'title' => $request->nom,
                'description' => $request->description,
                'start_time' => $startTime,
                'end_time' => $request->deadline,
                'typeevent' => "Project",
            ]);
            
            // Envoyer la notification à chaque utilisateur
            $users = User::where('privileges', '<>', 'client')->get();
            NotifyUsersAboutEvents::dispatch($event, $users);


            $project->chefprojet()->associate($request->get('chef_id'));
            $project->save();
            if ($project) {
                return response()->json([
                    'status' => 200,
                    'message' => "Project Created Successfully",
                    'project' => $project,
                    'password' => $word,

                ], 200);

            }


        }

    }


    public function update(Request $request, $id)
    {

        //    $request=$request->json();


        $project = Project::find($id);

        if ($request->get('chef_id')) {
            $project->chefprojet()->associate($request->get('chef_id'));
            $project->save();
        }
        $project->update($request->all());
        return response()->json([
            'status' => 200,
            'message' => 'your project has been updated statusfully',
            'project' => $project
        ], 200);
    }

    public function search(Request $request)
    {
        $search = $request->search;
        if ($search == 'true') {
            $search = 1;
        } else {
            $search = 0;
        }
        $projects = Project::where(function ($query) use ($search) {
            $query->where('nom', 'like', '%' . $search . '%')
                ->orwhere('retard', 'like', '%' . $search . '%');
        })
            ->orwherehas('Client', function ($query) use ($search) {
                $query->where('nom', 'like', '%' . $search . '%');
            });
        if ($projects) {
            return response()->json([
                'status' => 200,
                'project' => $projects->get()
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'project not fount',
            ], 401);
        }

    }

    public function show($id)
    {

        $project = Project::findorfail($id);
        if ($project) {
            return response()->json([
                'status' => 200,
                'message' => "your project has been found statusfuly",
                'project' => $project
            ], 200);
        } else {

            return response()->json([
                'status' => 404,
                'message' => 'project not found'
            ], 404);
        }

    }

    public function login(Request $request)
    {
        //$request=$request->json();

        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|min:6|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'validation errors',
                'ERRORS' => $validator->errors(),

            ], 422);
        }
        $credentials = [
            'username' => $request->get('username'),
            'password' => $request->get('password'),
        ];

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 404,
                'message' => 'username or password does not exist in our field',
            ], 404);

        }
        $user = Auth::user();
        $token = $user->createToken(' Token')->accessToken;
        return
            response()->json([
                'status' => 200,
                'message' => 'connected successfuly',
                'user' => $user,
                'token' => $token,

            ], 200);

    }



}


