<?php

namespace App\Models;

use App\Models\Employe;
use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Departement extends Model
{
    use HasFactory;

    protected $table = 'departements';

    protected $fillable = [
        'nom',
        'fonction'
    ];

    public function employes(){
        return $this->hasMany(Employe::class);
    }

    public function projects(){
        return $this->hasMany(Project::class);
    }

}
