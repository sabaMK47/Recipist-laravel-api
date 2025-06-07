<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecipeController extends Controller
{
    public function index(Request $request)
{
    $query = Recipe::query();

    // Filter by ingredient keyword (matches NER array content)
    if ($request->has('ingredients')) {
        $ingredients = explode(',', $request->ingredients);
        foreach ($ingredients as $ingredient) {
            $query->whereJsonContains('NER', trim($ingredient));
        }
    }

    if ($request->has('title')) {
        $query->where('title', 'like', '%' . $request->title . '%');
    }

    if ($request->has('genre')) {
        $query->where('genre', $request->genre);
    }

    return response()->json($query->paginate(20));
}

public function show($id)
{
    $recipe = Recipe::findOrFail($id);
    return response()->json($recipe);
}


}
