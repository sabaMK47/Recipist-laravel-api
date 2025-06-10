<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RecipeController;
use App\Models\Recipe;

Route::get('/recipes', [RecipeController::class, 'index']);

Route::get('/recipes/{id}', [RecipeController::class, 'show']);

Route::get('/genres-list', function () {
    $genres = Recipe::distinct()->pluck('genre').toArray();
    dd($genres);
});


