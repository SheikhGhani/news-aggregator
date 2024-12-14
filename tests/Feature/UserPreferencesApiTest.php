<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use App\Models\UserPreference;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserPreferencesApiTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $token;

    /**
     * Set up a fake user and token for authentication.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create a fake user
        $this->user = User::factory()->create();

        // Generate a personal access token for the user
        $this->token = $this->user->createToken('TestToken')->plainTextToken;

        // Set the Authorization header for subsequent requests
        $this->withHeader('Authorization', 'Bearer ' . $this->token);
    }

    /**
     * Test getUserPreferences method.
     *
     * @return void
     */
    public function testGetUserPreferences()
    {
        // Seed user preferences
        UserPreference::create([
            'user_id' => $this->user->id,
            'preference_type' => 'source',
            'preference_value' => 'TechSource',
        ]);

        // Call the getUserPreferences endpoint
        $response = $this->getJson('/api/preferences');

        // Assert the response status and structure
        $response->assertStatus(200)
            ->assertJsonFragment([
                'preference_type' => 'source',
                'preference_value' => 'TechSource',
            ]);
    }

    /**
     * Test setUserPreference method.
     *
     * @return void
     */
    public function testSetUserPreference()
    {
        // Make a request to set a preference
        $response = $this->postJson('/api/preferences', [
            'preference_type' => 'category',
            'preference_value' => 'Technology',
        ]);

        // Assert the response status
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Preference saved successfully',
            ]);

        // Assert the preference is saved in the database
        $this->assertDatabaseHas('user_preferences', [
            'user_id' => $this->user->id,
            'preference_type' => 'category',
            'preference_value' => 'Technology',
        ]);

        // Ensure cache is invalidated (using Cache::has)
        $userPreferencesCacheKey = "user_preferences_{$this->user->id}";
        $personalizedFeedCacheKey = "personalized_news_feed_{$this->user->id}";
        $this->assertFalse(Cache::has($userPreferencesCacheKey));
        $this->assertFalse(Cache::has($personalizedFeedCacheKey));
    }

    /**
     * Test getPersonalizedNewsFeed method.
     *
     * @return void
     */
    public function testGetPersonalizedNewsFeed()
    {
        // Seed user preferences
        UserPreference::create([
            'user_id' => $this->user->id,
            'preference_type' => 'category',
            'preference_value' => 'Technology',
        ]);

        // Seed articles
        Article::factory()->count(10)->create(['category' => 'Technology']);
        Article::factory()->count(5)->create(['category' => 'Science']);

        // Call the getPersonalizedNewsFeed endpoint
        $response = $this->getJson('/api/preferences/news-feed');

        // Assert the response status and check for "Technology" category articles
        $response->assertStatus(200)
            ->assertJsonFragment(['category' => 'Technology'])
            ->assertJsonMissing(['category' => 'Science']);

        // Assert that the data is cached by checking if the cache key exists
        $cacheKey = "personalized_news_feed_{$this->user->id}";
        $this->assertTrue(Cache::has($cacheKey)); // Cache should be set
    }
}
