<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employes', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->date('date_naissance');
            $table->string('email');
            $table->string('adresse');
            $table->string('telephone', 10)->nullable(false);
            $table->enum('role', array('EMPLOYE','CHEF_PROJET', 'ADMINISTRATEUR'));
            $table->unsignedBigInteger('departement_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();




            $table->foreign('departement_id')
            ->references('id')
            ->on('departements')
            ->onDelete('cascade');


            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade'); // Supprime l'employé associé si l'utilisateur est supprimé
        });


    }


    public function down(): void
    {
        //
    }
};
