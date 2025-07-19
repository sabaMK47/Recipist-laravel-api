<?php

namespace App\Console\Commands;
use App\Models\Recipe;
use App\Services\ElasticsearchService;
use Illuminate\Console\Command;

class IndexRecipesElasticsearch extends Command
{
    protected $signature = 'recipes:index';
    protected $description = 'Index all recipes in Elasticsearch';

    public function handle(ElasticsearchService $es)
    {
        Recipe::with('ingredients')->chunk(100, function ($recipes) use ($es) {
            foreach ($recipes as $recipe) {
                $es->getClient()->index([
                    'index' => 'recipes',
                    'id'    => $recipe->id,
                    'body'  => [
                        'name' => $recipe->name,
                        'ingredients' => $recipe->ingredients->pluck('name')->toArray(),
                    ],
                ]);
            }
        });

        $this->info('All recipes indexed in chunks!');
    }

}