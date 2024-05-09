<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'fonction',
        'num_telf',
        'mail',
        

    ];

    public function projects(){
        return   $this->hasMany(Project::class,'client_id','id');
            }

    
            public function users(){
                return   $this->belongsTo(User::class,'user_id','id');
                    }       
}
