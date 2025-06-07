<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RecipeController;

Route::get('/recipes', [RecipeController::class, 'index']);

Route::get('/recipes/{id}', [RecipeController::class, 'show']);


