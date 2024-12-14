<?php

namespace Tests\Feature\Article;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Article;
use App\Models\User;

class ArticleApiTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    /**
     * Set up a fake user and token for authentication.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create a fake user
        $this->user = User::factory()->create();

        // Generate a personal access token for the user
        $token = $this->user->createToken('TestToken')->plainTextToken;

        // Set the Authorization header for subsequent requests
        $this->withHeader('Authorization', 'Bearer ' . $token);
    }

    public function testGetArticlesReturnsPaginatedResults()
    {
        Article::factory()->count(15)->create();

        $response = $this->getJson('/api/articles');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'current_page',
                'data' => [
                    '*' => ['id', 'title', 'content', 'published_at', 'category', 'source']
                ],
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links' => [
                    '*' => ['url', 'label', 'active']
                ],
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total',
            ],
            'error',
            'code',
        ]);

        $this->assertCount(10, $response->json('data.data'));
    }

    public function testGetArticlesWithKeywordFilter()
    {
        Article::factory()->create(['title' => 'Laravel Testing']);
        Article::factory()->create(['title' => 'PHP Unit Testing']);

        $response = $this->getJson('/api/articles?keyword=Laravel');

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Laravel Testing'])
            ->assertJsonMissing(['title' => 'PHP Unit Testing']);
    }

    public function testGetArticleByIdSuccess()
    {
        $article = Article::factory()->create();

        $response = $this->getJson("/api/articles/{$article->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => $article->title]);
    }

    public function testGetArticleByIdNotFound()
    {
        $response = $this->getJson('/api/articles/999');

        $response->assertStatus(404)
            ->assertJson(['message' => 'Article not found']);
    }
}
