<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use App\Models\User;

use Illuminate\http\Request;
use Illuminate\support\Facades\Validator;
use Illuminate\support\Facades\Auth;
use Illuminate\support\Facades\Hash;


class userController extends Controller
{
    public function signup(Request $request)
    {

        $request = $request->json();
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users',
            'password' => 'required|min:6|max:8',
            'privileges' => 'required'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'succes' => false,
                'message' => 'validation errors',
                'error' => $validator->errors(),
                'request' => $request->all(),
                'product' => User::all()
            ], 401);
        } else {


            $user = User::Create([
                'username' => $request->get('username'),
                'password' => Hash::make($request->get('password')),
                'privileges' => $request->get('privileges')

            ]);

            return response()->json([
                'succes' => true,
                'message' => 'user created successfuly',
                'token' => $user->createToken('API TOKEN'),
                'user' => $user::all()
            ], 200);
        }
    }





    // public function login(Request $request){
    //     //  dd($request);
    //     $validator=Validator::make($request->all(),[
    //         'username'=>'required',
    //         'password'=>'required|min:6|max:8'

    //     ]);

    //     if($validator->fails()){
    //         return response()->json([
    //             'status'=>422,
    //             'message'=>'validation errors',
    //             'ERRORS'=>$validator->errors(),
    //             'request'=>$request
    //         ],422);
    //     }
    //     $credentials = [
    //         'username' => $request->get('username'),
    //         'password' => $request->get('password'),
    //     ];

    //     // dd($credentials);

    //     if(!Auth::attempt($credentials)){

    //             return response()->json([
    //                 'status'=>404,
    //                 'message'=>'username or password does not exist in our field',
    //             ],404);

    //     }

    //  $user=User::Auth();

    //  return
    //     response()->json([
    //         'status'=>200,
    //         'message'=>'connected successfuly',
    //         'user'=>$user,
    //         'token' => $user->createToken('API Token')
    //     ],200);

    // }

    public function index()
    {
        $users = User::all();
        if ($users) {
            return response()->json([
                'status' => 200,
                'message' => "users uploaded succesfully",
                'users' => $users
            ], 200);
        }
    }
    //   auth()->user()->token()->revoke();
    public function logout()
    {
        //  $user=User::Auth();
        auth()->user()->token()->revoke();

        return response()->json([
            'status' => 200,
            'message' => 'logout succesfulyy',

        ], 200);
    }



    public function userInfo(Request $request)
    {
        $user = $request->user(); // Récupérer l'utilisateur actuel
        $employee = $user->employee; // Récupérer l'employé associé à cet utilisateur

        // Maintenant vous pouvez accéder aux informations de l'employé comme ceci :
        $nomEmploye = $employee->nom;
        $dateNaissance = $employee->date_naissance;
        // etc.

        // Vous pouvez également récupérer les informations de l'utilisateur lui-même
        $nomUtilisateur = $user->name;
        $email = $user->email;
        

        // Retournez les informations dans votre vue ou réponse JSON
    }


    //************************************ USER Profile Picture *************************************************/
    public function uploadProfilePicture(Request $request)
    {
        // dd(request()->image);
        $request->validate([
            'image' => 'required|image', 
        ]);

        $user = Auth::user();
        $imagePath = $request->file('image')->store('profile_pictures', 'public');
        $user->image = $imagePath;
        $user->save();

        return response()->json(['success' => 'Profile picture uploaded successfully']);

    }

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'image' => 'required|image', 
        ]);

        $user = Auth::user();
        $imagePath = $request->file('image')->store('profile_pictures', 'public');
        $user->image = $imagePath;
        $user->save();

        return back()->with('success', 'Profile picture updated successfully.');
    }

    public function deleteProfilePicture()
    {
        $user = Auth::user();
        if ($user->image) {
            unlink(public_path('storage/' . $user->image)); 
            $user->image = null;
            $user->save();
        }

        return back()->with('success', 'Profile picture deleted successfully.');
    }





//************************************ USER Change Password *************************************************/


    public function changePassword(Request $request, $id)
    {
        // Valider les données du formulaire
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ]);

        // Récupérer l'utilisateur
        $user = User::findOrFail($id);

        // Vérifier si le mot de passe actuel correspond
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Le mot de passe actuel est incorrect.');
        }

        // Mettre à jour le mot de passe de l'utilisateur
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        // Redirection ou toute autre action nécessaire
        return redirect()->back()->with('success', 'Mot de passe mis à jour avec succès.');
    }
}
