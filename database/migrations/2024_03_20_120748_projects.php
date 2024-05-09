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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('description');
            $table->string('livrable');
            $table->string('objectif');
            $table->date('deadline');
            $table->boolean('retard'); 
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('chef_id')->nullable();
            $table->foreign('client_id')
            ->references('id')
            ->on('clients')
            -> onUpdate('NO ACTION ')
            -> onDelete('NO ACTION ');

            $table->foreign('chef_id')
            ->references('id')
            ->on('employes')
            -> onUpdate('NO ACTION ')
            -> onDelete('NO ACTION ');
            $table->timestamps();
        });    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
