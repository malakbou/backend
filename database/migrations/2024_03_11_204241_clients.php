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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->String('fonction');
            $table->string('num_telf');
            $table->string('mail')->unique();
            $table->unsignedBigInteger('user_id');

            $table->foreign('user_id')
            ->references('id')
            ->on('users')
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
        Schema::dropIfExists('clients');
    }
};
