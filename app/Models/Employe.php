<?php

namespace App\Models;

use App\Models\User;
use App\Models\Tache;
use App\Models\Project;
use App\Models\Departement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employe extends Model
{
    use HasFactory;

    protected $table = 'employes';

    protected $fillable = [
        'nom',
        'prenom',
        'date_naissance',
        'email',
        'adresse',
        'telephone',
        'role',
        'departement_id',
        'user_id'
    ];

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function taches(){
        return $this->hasMany(Tache::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function projects(){
        return $this->hasMany(Project::class);
    }


}

