<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Article;
use Carbon\Carbon;

class FetchNewArticles extends Command
{
    protected $signature = 'fetch:news-sources';
    protected $description = 'Fetch articles from dynamic news sources and store them in the database';

    public function handle()
    {
        $this->info("Fetching articles...");

        // Fetch articles for each source
        $this->fetchArticlesFromNewsAPI();
        $this->fetchArticlesFromNewYorkTimes();
        $this->fetchArticlesFromTheGuardian();
    }

    // Fetch articles from NewsAPI
    public function fetchArticlesFromNewsAPI()
    {
        $sourceName = config('constants.sources.news_api');
        $sourceConfig = config('news_sources.sources.' . $sourceName);
        $this->info("Fetching articles from source: $sourceName");

        $response = Http::get($sourceConfig['base_url'], $sourceConfig['params']);
        if ($response->failed()) {
            $this->error("Failed to fetch articles from $sourceName.");
            return;
        }

        $articles = $this->getArticlesFromNewsAPI($response->json());

        if (empty($articles)) {
            $this->info("No articles found for source: $sourceName.");
            return;
        }

        $fetchedCount = 0;
        foreach ($articles as $article) {
            $publishedAt = isset($article['publishedAt']) && !empty($article['publishedAt'])
                ? Carbon::parse($article['publishedAt'])->format('Y-m-d H:i:s')
                : now()->format('Y-m-d H:i:s');

            $isCreated = Article::updateOrCreate(
                ['url' => $article['url']],
                [
                    'title' => $article['title'],
                    'content' => $article['content'] ?? 'N/A',
                    'source' => $article['source']['name'] ?? $sourceName,
                    'author' => $article['author'] ?? 'Unknown',
                    'category' => 'General',  // Adjust as needed
                    'published_at' => $publishedAt,
                    'url' => $article['url'],
                ]
            );

            if ($isCreated->wasRecentlyCreated || $isCreated->wasChanged()) {
                $fetchedCount++;
            }
        }

        $this->info("Fetched and stored $fetchedCount unique articles from $sourceName.");
    }

    // Fetch articles from New York Times
    public function fetchArticlesFromNewYorkTimes()
    {
        $sourceName = config('constants.sources.newyork_times');
        $sourceConfig = config('news_sources.sources.' . $sourceName);
        $this->info("Fetching articles from source: $sourceName");

        $response = Http::get($sourceConfig['base_url'], $sourceConfig['params']);
        if ($response->failed()) {
            $this->error("Failed to fetch articles from $sourceName.");
            return;
        }

        $articles = $this->getArticlesFromNewYorkTimes($response->json());

        if (empty($articles)) {
            $this->info("No articles found for source: $sourceName.");
            return;
        }

        $fetchedCount = 0;
        foreach ($articles as $article) {
            $publishedAt = isset($article['published_date']) && !empty($article['published_date'])
                ? Carbon::parse($article['published_date'])->format('Y-m-d H:i:s')
                : now()->format('Y-m-d H:i:s');

            $isCreated = Article::updateOrCreate(
                ['url' => $article['url']],
                [
                    'title' => $article['title'],
                    'content' => $article['abstract'] ?? 'N/A',
                    'source' => 'New York Times',
                    'author' => $article['byline'] ?? 'Unknown',
                    'category' => 'General',
                    'published_at' => $publishedAt,
                    'url' => $article['url'],
                ]
            );

            if ($isCreated->wasRecentlyCreated || $isCreated->wasChanged()) {
                $fetchedCount++;
            }
        }

        $this->info("Fetched and stored $fetchedCount unique articles from $sourceName.");
    }

    // Fetch articles from another source
    public function fetchArticlesFromTheGuardian()
    {
        $sourceName = config('constants.sources.the_guardian');
        $sourceConfig = config('news_sources.sources.' . $sourceName);
        $this->info("Fetching articles from source: $sourceName");

        $response = Http::get($sourceConfig['base_url'], $sourceConfig['params']);
        if ($response->failed()) {
            $this->error("Failed to fetch articles from $sourceName.");
            return;
        }

        $articles = $this->getArticlesFromTheGuardian($response->json());

        if (empty($articles)) {
            $this->info("No articles found for source: $sourceName.");
            return;
        }

        $fetchedCount = 0;
        foreach ($articles as $article) {
            $publishedAt = isset($article['webPublicationDate']) && !empty($article['webPublicationDate'])
                ? Carbon::parse($article['webPublicationDate'])->format('Y-m-d H:i:s')
                : now()->format('Y-m-d H:i:s');

            $isCreated = Article::updateOrCreate(
                ['url' => $article['url']],
                [
                    'title' => $article['title'],
                    'content' =>  $article['webUrl'] ?? '',
                    'source' => $article['source']['name'] ?? $sourceName,
                    'author' => $article['author'] ?? 'Unknown',
                    'category' => $article['pillarName'] ?? 'General',
                    'published_at' => $publishedAt,
                    'url' => $article['url'],
                ]
            );

            if ($isCreated->wasRecentlyCreated || $isCreated->wasChanged()) {
                $fetchedCount++;
            }
        }

        $this->info("Fetched and stored $fetchedCount unique articles from $sourceName.");
    }

    // Function to extract articles from NewsAPI response
    private function getArticlesFromNewsAPI($response)
    {
        return $response['articles'] ?? [];
    }

    // Function to extract articles from New York Times response
    private function getArticlesFromNewYorkTimes($response)
    {
        return $response['results'] ?? [];
    }

    // Function to extract articles from another source
    private function getArticlesFromTheGuardian($response)
    {
        // Customize this function based on the response structure of another source
        return $response['results'] ?? [];
    }
}
