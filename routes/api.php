<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RecipeController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::options('{any}', function () {
    return response()->json([], 200);
})->where('any', '.*');

Route::get('/recipes', [RecipeController::class, 'index']);

Route::get('/recipes/random', [RecipeController::class, 'random']);

Route::get('/recipes/search', [RecipeController::class, 'search']);

Route::get('/recipes/{id}', [RecipeController::class, 'show']);

?>
