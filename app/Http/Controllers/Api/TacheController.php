<?php

namespace App\Http\Controllers\Api;

use App\Notifications\TacheCreated;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller ;
use App\Models\Tache;
use App\Models\Project;
use App\Enums\TachePrioriteEnum;
use App\Enums\TacheStatusEnum;
use Illuminate\support\Facades\Validator;

class TacheController extends Controller
{

public function index(){
    $taches=Tache::all();
    return response()->json([
        'status' => 200,
        'message' => 'all taches has been extracted succesfuly',
        'taches'=>$taches
    ], 200);  
    }

    public function show($id){
        $tache=Tache::find($id);
        if($tache){
        return response()->json([
            'status' => 200,
            'message' => 'your task :',
            'taches'=>$tache
        ], 200);  }
        else{
            return response()->json([
                'status' => 404,
                'message' => 'task does not exist',
                
            ], 200);
        }
        }

public function store(Request $request,$id){

  // $request=$request->json();
    $validator=Validator::make($request->all(),[
        'nom'=>'required',
        'description'=>'required',
        'datefin'=>'required|date_format:Y-m-d',
        'status'=>'required',
        'priorite'=>'required',
        'employe_id'=>'required|exists:employes,id'
    ]);

    
  if($validator->fails()){
        return response()->json([
            'status' => 422,
            'message'=>'validation errors',
            'ERRORS'=>$validator->errors(),
            'request'=> $request->all(),
           
        ],422);
    }else{

$project=Project::find($id);

if (!$project) {
    return response()->json([
        'success' => false,
        'message' => 'Project not found',
    ], 404);
}

$tache=$project->taches()->create([
'nom'=> $request->get('nom'),
'description'=>$request->get('description'),
'datefin'=>$request->get('datefin'),
'status'=>$request->get('status'),
'priorite'=>$request->get('priorite')

]);

$tache->employes()->associate($request->get('employe_id'));
$tache->save();

 // Envoyer la tache a l'utilisateur 
$tache->employes->user->notify(new TacheCreated($tache));

return response()->json([
    'status' => 200,
    'message'=>'tache created successfuly',
   'tache'=>$tache
],200);

}



}



public function update(Request $request ,$id){
    $validator=Validator::make($request->all(),[
        'nom'=>'required',
        'description'=>'required',
        'datefin'=>'required|date_format:Y-m-d',
        'status'=>'required',
        'priorite'=>'required',
        'employe_id'=>'required|exists:employes,id'
    ]);

    
  if($validator->fails()){
        return response()->json([
            'status' => 422,
            'message'=>'validation errors',
            'ERRORS'=>$validator->errors(),
            'request'=> $request->all(),
           
        ],422);
    }else{
    $tache=Tache::find($id);
      if($request->get('employe_id')){
        $tache->employes()->associate($request->get('employe_id'));
        $tache->save();
      };
    $tache->update($request->all());
        return response()->json([
            'status'=>200,
            'message'=>'your tache has been updated succesfully',
            'tache'=>tache::find($id)
        ],200);
}}

public function updatestatus(Request $request,$id){
    $status=$request->status;
    $tache=Tache::find($id);
    $tache->update(['status'=>$status]);
    return response()->json([
        'status'=>200,
        'message'=>'your tache has been updated succesfully',
        'tache'=>Tache::find($id)
    ],200);

}

public function delete($id){
    $tache=Tache::find($id);
    $tache->delete();
    return response()->json([
        'status'=>200,
        'message'=>'your tache has been deleted succesfully',
        
    ],200);
}

public function showproject($id){
    
    $tache=Tache::where('projet_id',$id)->get();
    if($tache){
        return(
            response()->json([
                'status'=>200,
                'message'=>'your tache  exist',
                'tache'=>$tache
            ],200)
        );
    }
}

public function showemploye($id){
    
    $tache=Tache::where('employe_id',$id)->get();
    if($tache){
        return(
            response()->json([
                'status'=>200,
                'message'=>'your tache exist',
                'tache'=>$tache
            ],200)
        );
    }
}
}

