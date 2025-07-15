<?php

// app/Console/Commands/FetchRecipeImages.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Recipe;
use Illuminate\Support\Facades\Http;

class FetchRecipeImages extends Command
{
    protected $signature = 'recipes:fetch-images';
    protected $description = 'Fetch image URLs from Unsplash based on recipe name';

    public function handle()
    {
        $recipes = Recipe::whereNull('image_url')->take(50)->get();

        foreach ($recipes as $recipe) {
            $query = urlencode($recipe->name . ' food');

            $response = Http::withHeaders([
                'Authorization' => 'Client-ID ' . env('UNSPLASH_ACCESS_KEY'),
            ])->get("https://api.unsplash.com/search/photos?query={$query}&per_page=1");

            if ($response->ok() && $response->json('results.0.urls.regular')) {
                $imageUrl = $response->json('results.0.urls.regular');
                $recipe->image_url = $imageUrl;
                $recipe->save();

                $this->info("Saved image for: {$recipe->name}");
            } else {
                $this->error("No image found for: {$recipe->name}");
            }

            sleep(1); // Respect API rate limit
        }
    }
}


