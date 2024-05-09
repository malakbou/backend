<?php

namespace App\Http\Controllers\Api;
use Illuminate\Routing\Controller ;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\support\Facades\Validator;


class ClientController extends Controller
{

    public function index(){
        $client=Client::all();
        if($client){
            return response()->json([
                'succes'=>true,
                'message'=>'your clients:',
                'client'=>$client
            ],200);
        }else{
            return response()->json([
                'succes'=>true,
                'message'=>' client not found ,table empty',
            
            ],401);
        }

    }
    public function update(Request $request,$id){
    $validator=Validator::make($request->all(),[
        'nom' => 'required|unique:clients',
        'fonction' => 'required',
        'num_telf' => 'required|unique:clients',
        'mail' => 'required|email',
    ]);

        $client=Client::find($id);           
        if($client){
        $client->update($request->all());
        return response()->json([
            'succes'=>true,
            'message'=>'your client has been updated succesfully',
            'cliennt'=>$client
        ],200);
    }else{
        return response()->json([
            'succes'=>true,
            'message'=>' client not found',
        
        ],401);
    }
    }

    public function search (Request $request){
        $nom=$request->search;
        $client=Client::where('nom','like','%'.$nom.'%')->get();
        if($client){
        return response()->json([
            'status'=>200,
            'message'=>'your client has been found succesfully',
            'client'=>$client
        ],200);}
        else{
            return response()->json([
                'succes'=>true,
                'message'=>'your client doesnt exist',
                
            ],200);
        }
    }

    public function show($id){
    
        $client=Client::find($id);
        if($client){
            return(
                response()->json([
                    'status'=>200,
                    'message'=>'your client  exist',
                    'client'=>$client
                ],200)
            );
        }
    }
}
