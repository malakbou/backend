<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'livrable',
        'objectif',
        'deadline',
        'retard'

    ];

    public function client(){
        return   $this->belongsTo(Client::class,'client_id','id');
        }


    public function taches(){
return   $this->hasMany(tache::class,'projet_id','id');
    }

    public function chefprojet(){
        return
               $this->belongsTo(Employe::class,'chef_id','id');
            }     
}
