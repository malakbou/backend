<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TachePrioriteEnum;
use App\Enums\TacheStatusEnum;

class Tache extends Model
{
    use HasFactory;
    protected $fillable=[
    'nom',
    'description',
    'datefin',
    'status',
    'priorite'
    ];

    protected $cast=[
        'status'=>TacheStatusEnum::class,
        'priorite'=>TacheprioriteEnum::class,
    ];



    public function projects(){
      return  $this->belongsTo(Project::class,'projet_id','id');
    }

    public function employes(){
      return  $this->belongsTo(Employe::class,'employe_id','id');
    }
}
