<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;
use App\Models\Ingredient;
use Illuminate\Support\Facades\DB;

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

        $file = base_path('database/data/3A2M.csv');

        if (!file_exists($file)) {
            echo "CSV file not found!\n";
            return;
        }

        $handle = fopen($file, 'r');
        $header = fgetcsv($handle); // Skip header row

        $counter = 0;
        while (($row = fgetcsv($handle)) !== false) {
            try {
                $recipe = Recipe::create([
                    'title' => $row[1],
                    'directions' => $this->parseFakeJson($row[2]),
                    'NER' => $this->parseFakeJson($row[3]),
                    'genre' => $row[4],
                    'label' => (int) $row[5],
                ]);

                $counter++;
                if ($counter % 500 === 0) {
                    echo "Inserted {$counter} recipes...\n";
                }

            } catch (\Exception $e) {
                echo "Error with recipe '{$row[0]}': " . $e->getMessage() . "\n";
            }
        }

        fclose($handle);
        echo "Seeder finished. Total inserted: {$counter} recipes.\n";
    }

    private function parseFakeJson(string $value): array
    {
        // Replace single quotes with double quotes to convert Python-style list to JSON
        $json = str_replace("'", '"', $value);
        $decoded = json_decode($json, true);
        return is_array($decoded) ? $decoded : [];
    }
}
