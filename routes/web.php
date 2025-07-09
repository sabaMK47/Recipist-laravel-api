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

Route::post('/login',[AuthController::class,'login']);
Route::post('/verifyMobile',[AuthController::class,'verifyMobile']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user',function(Request $request){
        return $request->user();
    });
    Route::post('/logout',[AuthController::class,'logout']);

});


