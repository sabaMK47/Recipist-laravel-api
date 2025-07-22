<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use App\Services\ElasticsearchService;



class RecipeController extends Controller
{
    public function index(Request $request)
    {
        $query = Recipe::query();

        // Filter by ingredients (optional: update this to your new logic)
        if ($request->has('ingredients')) {
            $ingredients = explode(',', $request->ingredients);
            foreach ($ingredients as $ingredient) {
                $query->whereHas('ingredients', function ($q) use ($ingredient) {
                    $q->where('name', 'like', '%' . trim($ingredient) . '%');
                });
            }
        }

        // Filter by title
        if ($request->has('title')) {
            $query->where('name', 'like', '%' . $request->title . '%');
        }

        // Filter by tags (match any)
        if ($request->has('tags')) {
            $tags = explode(',', $request->tags);
            foreach ($tags as $tag) {
                $query->whereJsonContains('tags', trim($tag));
            }
        }

        return response()->json($query->with('ingredients')->paginate(20));
    }

    public function show($id)
    {
        $recipe = Recipe::with('ingredients')->findOrFail($id);
        return response()->json($recipe);
    }

   public function random()
    {
        // Cache the random recipe for 24 hours (1440 minutes)
        $recipe = Cache::remember('daily_random_recipe', 60 * 24, function () {
            return Recipe::with('ingredients')->inRandomOrder()->first();
        });

        return response()->json($recipe);
    }

     public function search(Request $request, ElasticsearchService $es)
    {
        $query = $request->input('q');
        $ingredients = $request->input('ingredients'); // expects comma-separated

        $must = [];

        if ($query) {
            $must[] = [
                'match' => [
                    'name' => [
                        'query' => $query,
                        'fuzziness' => 'AUTO'
                    ]
                ]
            ];
        }

        if ($ingredients) {
            $ingredientArray = array_map('trim', explode(',', $ingredients));
            foreach ($ingredientArray as $ingredient) {
                $must[] = [
                    'match' => [
                        'ingredients' => $ingredient
                    ]
                ];
            }
        }

        $response = $es->getClient()->search([
            'index' => 'recipes',
            'body'  => [
                'size' => 20,
                'query' => [
                    'bool' => [
                        'must' => $must
                    ]
                ]
            ]
        ]);

        $results = collect($response['hits']['hits'])->pluck('_source');

        return response()->json($results);
    }

}
