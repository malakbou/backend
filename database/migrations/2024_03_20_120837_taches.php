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
        Schema::create('taches', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description');
            $table->date('datefin');
            $table->enum('status',['completed','in progress','not assigned']);
            $table->enum('priorite',['urgent','normal','faible']);
    
            $table->unsignedBigInteger('projet_id');
            $table->foreign('projet_id')
            ->references('id')
            ->on('projects')
            -> onUpdate('NO ACTION ')
            -> onDelete('NO ACTION ');
    
            $table->unsignedBigInteger('employe_id')->nullable();
            $table->foreign('employe_id')
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
