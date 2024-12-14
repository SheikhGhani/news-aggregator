<?php

namespace App\Http\Services;

use App\Models\UserPreference;
use App\Models\Article;
use Illuminate\Support\Facades\Cache;

class UserPreferenceService extends BaseService
{
    public function getUserPreferences($user)
    {
        try {
            $userId = auth()->user()->id;
            $cacheKey = "user_preferences_{$userId}";
            $preferences = Cache::remember($cacheKey, 60, function () use ($userId) {
                return UserPreference::where('user_id', $userId)->get();
            });
            $preferences = UserPreference::where('user_id', $userId)->get();
            return $this->sendSuccessResponseJson($preferences, 'Preferences retrieved successfully');
            
        } catch (\Exception $e) {
            return $this->sendErrorResponseJson('Failed to retrieve preferences', [$e->getMessage()]);
        }
    }

    public function setUserPreference($request)
    {
        $request->validate([
            'preference_type' => 'required|in:source,category,author',
            'preference_value' => 'required|string',
        ]);

        try {
            $userId = $request->user()->id;
            UserPreference::updateOrCreate(
                [
                    'user_id' => $userId,
                    'preference_type' => $request->preference_type,
                    'preference_value' => $request->preference_value,
                ]
            );

            //Invalidate the cache if the preferences are updated
            Cache::forget("user_preferences_{$userId}");
            Cache::forget("personalized_news_feed_{$userId}");

            return $this->sendSuccessResponseJson([], 'Preference saved successfully');
        } catch (\Exception $e) {
            return $this->sendErrorResponseJson('Failed to save preference', [$e->getMessage()]);
        }
    }

    public function getPersonalizedNewsFeed($user)
    {
        try {
            $cacheKey = "personalized_news_feed_{$user->id}";

            $articles = Cache::remember($cacheKey, 3600, function () use ($user) {
                $preferences = UserPreference::where('user_id', $user->id)->get();
                $query = Article::query();

                foreach ($preferences as $preference) {
                    switch ($preference->preference_type) {
                        case 'source':
                            $query->orWhere('source', $preference->preference_value);
                            break;
                        case 'category':
                            $query->orWhere('category', $preference->preference_value);
                            break;
                        case 'author':
                            $query->orWhere('author', $preference->preference_value);
                            break;
                    }
                }

                return $query->paginate(10);
            });
            return $this->sendSuccessResponseJson($articles, 'Personalized news feed retrieved successfully');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch personalized news feed'], 500);
        }
    }
}
