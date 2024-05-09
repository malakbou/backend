<?php

namespace App\Http\Controllers\Api;

// il faut emplementer EmployeResource
use dump;
use App\Models\User;
use App\Models\Employe;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeResource;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;


use Illuminate\Support\Facades\Hash;
use Illuminate\support\Facades\Auth;

class EmployeC extends Controller
{


    // public function login(Request $request)
    // {
    //     //$request=$request->json();

    //     $validator = Validator::make($request->all(), [
    //         'username' => 'required',
    //         'password' => 'required|min:6|max:20'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 422,
    //             'message' => 'validation errors',
    //             'ERRORS' => $validator->errors(),

    //         ], 422);
    //     }
    //     $credentials = [
    //         'username' => $request->get('username'),
    //         'password' => $request->get('password'),
    //     ];

    //     if (!Auth::attempt($credentials)) {
    //         return response()->json([
    //             'status' => 404,
    //             'message' => 'username or password does not exist in our field',
    //         ], 404);

    //     }
    //     $user = Auth::user();
    //     $token = $user->createToken('Token')->accessToken;
    //     return
    //         response()->json([
    //             'status' => 200,
    //             'message' => 'connected successfuly',
    //             'user' => $user,
    //             'token' => $token,

    //         ], 200);

    // }





    /**************************** SHOW *****************************/
    public function show($id)
    {
        $employe = Employe::find($id);

        if (!$employe) {
            return response()->json(['message' => 'Employé non trouvé'], 404);
        }

        return response()->json($employe);
    }


    /**************************** INDEX *****************************/
    public function index()
    {
        $employes = Employe::all();
        if ($employes->count() > 0) {
            return Response::json([
                'status' => 200,
                // EmployeResource::collection($employes) elle utilise EmployeResource pour afficher aussi nom departement
                // 'employesC' => $employes, 
                'employes' => EmployeResource::collection($employes),
            ], 200);
        } else {
            return Response::json([
                'status' => 404,
                'message' => 'No records found'
            ], 404);
        }
    }




    /**************************** STORE *****************************/
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:191',
            'prenom' => 'required|string|max:60',
            'date_naissance' => 'required|date',
            'email' => 'required|string|email|unique:employes', // Ensure email is unique within the employes table
            'adresse' => 'required|string',
            'telephone' => 'required|string|digits:10',
            'role' => 'required|string|in:CHEF_PROJET,EMPLOYE,ADMINISTRATEUR',
            'departement_id' => 'required|integer|exists:departements,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
                'request' => $request
            ], 422);
        } else {

            /******************** CREATE COMPTE EMPLOYE ********************/

            $word = $request->get('nom') . Str::random(5);
            $user = User::create([
                // 'username' => $request->get('nom') . '.' . $request->get('prenom'),
                'username' => $request->get('nom') . 'whitelineservices',
                'password' => Hash::make($word),
                'privileges' => $request->role
            ]);
            $token = $user->createToken('API Token');


            if (!$user) {
                return response()->json([
                    'success' => 404,
                    'message' => 'user creation failed ',
                    'password' => $word
                ], 404);
            }



            /************************ CREATE EMPLOYE ************************/
            $employe = Employe::create([
                'user_id' => $user->id,
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'date_naissance' => $request->date_naissance,
                'email' => $request->email,
                'adresse' => $request->adresse,
                'telephone' => $request->telephone,
                'role' => $request->role,
                'departement_id' => $request->departement_id,

            ]);

            return response()->json([
                'status' => 200,
                'message' => "Employe Created",
                'employe' => $employe,
                'password' => $word
            ], 200);
        }


    }

    /**************************** DELETE *****************************/
    public function destroy($id)
    {

        $employe = Employe::find($id);

        if ($employe) {
            $employe->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Employe Deleted Sucessfully'
            ], 200);

        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Employe Not Exist'
            ], 404);

        }
    }


    /**************************** UPDATE *****************************/

    public function update(Request $request, int $id)
    {

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:191',
            'prenom' => 'required|string|max:60',
            'date_naissance' => 'required|date',
            'email' => [
                'required',
                'string',
                'email',
                Rule::unique('employes')->ignore($id),
            ],
            'adresse' => 'required|string',
            'telephone' => 'required|string|digits:10',
            'role' => 'required|string|in:CHEF_PROJET,EMPLOYE,ADMINISTRATEUR',
            'departement_id' => 'required|integer|exists:departements,id',

        ]);


        if ($validator->fails()) {

            return response()->json([
                'status' => 422,
                'ERRORS' => $validator->messages()
            ], 422);

        } else {

            $employe = Employe::find($id);

            if ($employe) {

                $employe->update([
                    'nom' => $request->nom,
                    'prenom' => $request->prenom,
                    'date_naissance' => $request->date_naissance,
                    'email' => $request->email,
                    'adresse' => $request->adresse,
                    'telephone' => $request->telephone,
                    'role' => $request->role,
                    'departement_id' => $request->departement_id,
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => "Employe Updated Successfully"
                ], 200);

            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Employe Not Found'
                ], 404);
            }
        }


    }




    /************************ Search EMPLOYE  *************************/
    public function search(Request $request)
    {
        $nom = $request->search;
        $employe = Employe::where(function ($query) use ($nom) {
            $query->where('nom', 'like', '%' . $nom . '%')
                ->orwhere('prenom', 'like', '%' . $nom . '%')
                ->orwhere('role', 'like', '%' . $nom . '%');
        })->with('Departement')->get();

        if ($employe) {
            return response()->json([
                'status' => 200,
                'message' => 'employe has been found',
                'employe' => $employe
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'employe has not been found',
            ], 404);
        }
    }





    /********************** SHOW EMPLOYE GRADE **********************/
    public function indexchef()
    {
        $employes = Employe::where('role', 'CHEF_PROJET');
        if ($employes) {
            return response()->json([
                'succes' => true,
                'employes' => $employes->get(['id', 'nom', 'prenom']),
            ], 200);
        } else {
            return response()->json([
                'succes' => true,
                'message' => 'chef de projet not found',
            ], 401);
        }
    }





}
