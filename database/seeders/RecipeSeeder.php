<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;
use App\Models\Ingredient;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        $file = base_path('database/data/RAW_recipes.csv');

        if (!file_exists($file)) {
            echo "CSV file not found!";
            return;
        }

        $handle = fopen($file, 'r');
        $header = fgetcsv($handle); // skip header row

        while (($row = fgetcsv($handle)) !== false) {
            try {
                $recipe = Recipe::create([
                    'name' => $row[0],
                    'minutes' => (int) $row[2],
                    'contributor_id' => $row[3],
                    'submitted' => $row[4],
                    'tags' => json_decode($row[5], true) ?? [],
                    'nutrition' => json_decode($row[6], true) ?? [],
                    'n_steps' => (int) $row[7],
                    'steps' => json_decode($row[8], true) ?? [],
                    'description' => $row[9],
                    'ingredients' => json_decode($row[10], true) ?? [],
                    'n_ingredients' => (int) $row[11],
                ]);

                $ingredients = json_decode($row[10], true);
                if (is_array($ingredients)) {
                    foreach ($ingredients as $ingredientName) {
                        $ingredient = Ingredient::firstOrCreate([
                            'name' => strtolower(trim($ingredientName))
                        ]);
                        $recipe->ingredients()->attach($ingredient->id);
                    }
                }
            } catch (\Exception $e) {
                echo "Error with recipe '{$row[0]}': " . $e->getMessage() . "\n";
            }
        }

        fclose($handle);
    }
}
