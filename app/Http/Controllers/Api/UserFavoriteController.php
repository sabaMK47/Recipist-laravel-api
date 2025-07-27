<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Recipe; 

class UserFavoriteController extends Controller
{
    /**
     * 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleFavorite(Request $request)
    {
        $user = $request->user(); 
        $recipeId = $request->input('recipe_id');

        if (!$recipeId) {
            return response()->json(['message' => 'Recipe ID is required.'], 400);
        }

        $recipe = Recipe::find($recipeId);
        if (!$recipe) {
            return response()->json(['message' => 'Recipe not found.'], 404);
        }

        if ($user->favorites()->where('recipe_id', $recipeId)->exists()) {
            $user->favorites()->detach($recipeId);
            return response()->json(['message' => 'Recipe unfavorited', 'favorited' => false]);
        } else {
            $user->favorites()->attach($recipeId);
            return response()->json(['message' => 'Recipe favorited', 'favorited' => true]);
        }
    }

    /**
     * Retrieves all favorite recipes for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFavorites(Request $request)
    {
        $user = $request->user();

        $favoriteRecipes = $user->favorites()->with('ingredients')->get();

        if ($favoriteRecipes->isEmpty()) {
            return response()->json(['message' => 'No favorite recipes found for this user.', 'data' => []], 200);
        }

        return response()->json(['data' => $favoriteRecipes], 200);
    }
}