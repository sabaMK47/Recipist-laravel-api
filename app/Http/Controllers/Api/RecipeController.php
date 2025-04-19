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

        if ($request->has('ingredients')) {
            $ingredients = explode(',', $request->ingredients);
            $query->whereHas('ingredients', function ($q) use ($ingredients) {
                $q->whereIn('name', $ingredients);
            });
        }

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $recipes = $query->get();

        return response()->json($recipes);
    }

    public function show($id)
    {
        $recipe = Recipe::with('ingredients')->findOrFail($id);
        return response()->json($recipe);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'minutes' => 'nullable|integer',
            'description' => 'nullable|string',
            'ingredients' => 'array',
            'ingredients.*' => 'string'
        ]);

        $recipe = Recipe::create($validated);

        if (!empty($validated['ingredients'])) {
            $ingredientIds = collect($validated['ingredients'])->map(function ($name) {
                return Ingredient::firstOrCreate(['name' => strtolower($name)])->id;
            });
            $recipe->ingredients()->attach($ingredientIds);
        }

        return response()->json($recipe->load('ingredients'), 201);
    }

    public function update(Request $request, $id)
    {
        $recipe = Recipe::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'minutes' => 'sometimes|integer',
            'description' => 'sometimes|string',
            'ingredients' => 'array',
            'ingredients.*' => 'string'
        ]);

        $recipe->update($validated);

        if (isset($validated['ingredients'])) {
            $ingredientIds = collect($validated['ingredients'])->map(function ($name) {
                return Ingredient::firstOrCreate(['name' => strtolower($name)])->id;
            });
            $recipe->ingredients()->sync($ingredientIds);
        }

        return response()->json($recipe->load('ingredients'));
    }

    public function destroy($id)
    {
        $recipe = Recipe::findOrFail($id);
        $recipe->ingredients()->detach();
        $recipe->delete();

        return response()->json(['message' => 'Recipe deleted.']);
    }

}
