<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeResource extends JsonResource
{
    /**

     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'date_naissance' => $this->date_naissance,
            'email' => $this->email,
            'adresse' => $this->adresse,
            'telephone' => $this->telephone,
            'role' => $this->role,
            // si on a besoind dafficher pluseurs champs we use 'departement'=> $this->departement-
            'departement'=> $this->departement->id,//on a selectionner le nom de departemnt only we need him pour le dashboard list employe
            'departementN'=> $this->departement->nom,
            'user_id'=> $this->user_id
        ];
    }
}
