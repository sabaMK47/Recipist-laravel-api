<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RecipeController;

Route::get('/recipes', [RecipeController::class, 'index']);

Route::get('/recipes/{id}', [RecipeController::class, 'show']);

Route::post('/recipes', [RecipeController::class, 'store']);

Route::put('/recipes/{id}', [RecipeController::class, 'update']);

Route::delete('/recipes/{id}', [RecipeController::class, 'destroy']);

Route::get('/', function () {
    return view('welcome');
});
