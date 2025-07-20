<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ElasticsearchService;

class DeleteRecipesElasticsearchIndex extends Command
{
    protected $signature = 'elastic:delete-index {index=recipes}';
    protected $description = 'Delete an Elasticsearch index';

    public function handle(ElasticsearchService $es)
    {
        $index = $this->argument('index');

        try {
            $es->getClient()->indices()->delete(['index' => $index]);
            $this->info("Index '{$index}' deleted successfully.");
        } catch (\Exception $e) {
            $this->error("Failed to delete index '{$index}': " . $e->getMessage());
        }
    }
}
