<?php

namespace App\Http\Controllers\Api;

use App\Models\Departement;
use App\Models\Employe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class departementController extends Controller
{

    /**************************** SHOW NOM CLIENT ************************/
    public function search($id)
    {


        $departement = Departement::where('id', $id)->first();


        if ($departement) {
            return response()->json([
                'status' => 200,
                'message' => 'Client trouvé',
                'data' => $departement, // Retourner les données du client
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Aucun client trouvé avec le nom fourni.',
            ], 404);
        }
    }

    /**************************** INDEX *****************************/
    public function index()
    {
        $departements = Departement::all();
        if ($departements->count() > 0) {
            return Response::json([
                'status' => 200,
                'departements' => $departements
            ], 200);
        } else {
            return Response::json([
                'status' => 404,
                'departements' => 'No records found'
            ], 404);
        }
    }


    /**************************** STORE *****************************/

    public function store(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'nom' => 'required|string|max:191',
                'fonction' => 'required|string|max:500',
            ]
        );

        if ($validator->fails()) {

            return response()->json([
                'status' => 422,
                'ERRORS' => $validator->messages()
            ], 422);

        } else {

            $departement = Departement::create([
                'nom' => $request->nom,
                'fonction' => $request->fonction,
            ]);

            return response()->json([
                'status' => 200,
                'message' => "Departement Created"
            ], 200);
        }

    }


    /**************************** UPDATE *****************************/

    public function update(Request $request, int $id)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'nom' => 'required|string|max:191',
                'fonction' => 'required|string|max:300',
            ]
        );


        if ($validator->fails()) {

            return response()->json([
                'status' => 422,
                'ERRORS' => $validator->messages()
            ], 422);

        } else {

            $departement = Departement::find($id);

            if ($departement) {

                $departement->update([
                    'nom' => $request->nom,
                    'fonction' => $request->fonction,
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => "Departement Updated Successfully"
                ], 200);

            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Departement Not Found'
                ], 404);
            }
        }


    }


}
