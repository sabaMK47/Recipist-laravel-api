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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('minutes')->nullable();
            $table->unsignedBigInteger('contributor_id')->nullable();
            $table->date('submitted')->nullable();
            $table->json('tags')->nullable();
            $table->json('nutrition')->nullable();
            $table->integer('n_steps')->nullable();
            $table->json('steps')->nullable();
            $table->text('description')->nullable();
            $table->json('ingredients')->nullable();
            $table->integer('n_ingredients')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
