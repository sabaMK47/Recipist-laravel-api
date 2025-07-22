<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RecipeController;
use App\Models\Recipe;

// Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user',function(Request $request){
        return $request->user();
    });

});






