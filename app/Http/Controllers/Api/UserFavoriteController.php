<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserFavoriteController extends Controller
{
    public function toggleFavorite(Request $request)
{
    $user = $request->user();
    $recipeId = $request->input('recipe_id');

    if ($user->favorites()->where('recipe_id', $recipeId)->exists()) {
        // If already favorited, remove it (unlike)
        $user->favorites()->detach($recipeId);
        return response()->json(['favorited' => false]);
    } else {
        // Otherwise, add favorite
        $user->favorites()->attach($recipeId);
        return response()->json(['favorited' => true]);
    }
}

}
