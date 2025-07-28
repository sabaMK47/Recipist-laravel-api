<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RecipeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\UserFavoriteController;

Route::options('{any}', function () {
    return response()->json([], 200);
})->where('any', '.*');

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::get('/recipes', [RecipeController::class, 'index']);

Route::get('/recipes/random', [RecipeController::class, 'random']);

Route::get('/recipes/search', [RecipeController::class, 'search']);

Route::get('/recipes/{id}', [RecipeController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/user/favorites', [UserFavoriteController::class, 'toggleFavorite']);
    Route::get('/user/favorites', [UserFavoriteController::class, 'getFavorites']); 
});

?>
