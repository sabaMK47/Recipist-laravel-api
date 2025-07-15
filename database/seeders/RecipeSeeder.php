<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;
use App\Models\Ingredient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        echo "Seeder started...\n";

        // Disable foreign key checks and truncate tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Recipe::truncate();
        DB::table('ingredients')->truncate();
        DB::table('ingredient_recipe')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $file = base_path('database/data/RAW_recipes.csv');

        if (!file_exists($file)) {
            echo "CSV file not found at: $file\n";
            return;
        }

        $handle = fopen($file, 'r');
        $header = fgetcsv($handle);

        $map = array_flip($header);
        $counter = 0;

        while (($row = fgetcsv($handle)) !== false ) {
            try {
                $name = $row[$map['name']];
                $minutes = (int) $row[$map['minutes']];
                $tags = $this->parseJson($row[$map['tags']]);
                $nutrition = $this->parseJson($row[$map['nutrition']]);
                $steps = $this->parseJson($row[$map['steps']]);
                $description = $row[$map['description']];
                $ingredients = $this->parseJson($row[$map['ingredients']]);

                $recipe = Recipe::create([
                    'name' => $name,
                    'minutes' => $minutes,
                    'tags' => $tags,
                    'nutrition' => $nutrition,
                    'steps' => $steps,
                    'description' => $description,
                ]);

                foreach ($ingredients as $ingredientName) {
                    $ingredientName = trim(Str::lower($ingredientName));
                    if (!$ingredientName) continue;

                    $ingredient = Ingredient::firstOrCreate(['name' => $ingredientName]);
                    $recipe->ingredients()->attach($ingredient->id);
                }

                $counter++;
                if ($counter % 250 === 0) {
                    echo "Inserted {$counter} recipes...\n";
                }

            } catch (\Exception $e) {
                echo "Error with recipe at line {$counter}: " . $e->getMessage() . "\n";
            }
        }

        fclose($handle);
        echo "Seeder finished. Total inserted: {$counter} recipes.\n";
    }

    private function parseJson(string $value): array
    {
        $json = str_replace("'", '"', $value); // if values are Python-style
        $decoded = json_decode($json, true);
        return is_array($decoded) ? $decoded : [];
    }
}
