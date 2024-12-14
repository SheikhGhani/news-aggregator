<?php

namespace App\Http\Controllers\Api;

use App\Http\Services\UserPreferenceService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use App\Http\Controllers\Controller;

class UserPreferenceController extends Controller
{
    protected $userPreferenceService;

    public function __construct(UserPreferenceService $userPreferenceService)
    {
        $this->userPreferenceService = $userPreferenceService;
    }

    /**
     * @OA\Get(
     *     path="/api/preferences",
     *     tags={"User Preferences"},
     *     summary="Retrieve user preferences",
     *     description="Fetch the user's preferred news sources, categories, and authors.",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="preference_type", type="string", example="source"),
     *                 @OA\Property(property="preference_value", type="string", example="BBC News")
     *             )
     *         )
     *     ),
     *     security={{"sanctum":{}}}
     * )
     */
    public function index(Request $request)
    {
        return $this->userPreferenceService->getUserPreferences($request);
    }

    /**
     * @OA\Post(
     *     path="/api/preferences",
     *     tags={"User Preferences"},
     *     summary="Set user preferences",
     *     description="Save or update the user's preferred news sources, categories, and authors.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="preference_type", type="string", example="source"),
     *             @OA\Property(property="preference_value", type="string", example="BBC News")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Preference saved",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Preference saved successfully")
     *         )
     *     ),
     *     security={{"sanctum":{}}}
     * )
     */
    public function store(Request $request)
    {
        return $this->userPreferenceService->setUserPreference($request);
    }

    /**
     * @OA\Get(
     *     path="/api/preferences/news-feed",
     *     tags={"User Preferences"},
     *     summary="Fetch personalized news feed",
     *     description="Fetch a news feed personalized to the user's preferences.",
     *     @OA\Response(
     *         response=200,
     *         description="Personalized news feed",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Article Title"),
     *                 @OA\Property(property="content", type="string", example="Detailed content here."),
     *                 @OA\Property(property="author", type="string", example="John Doe"),
     *                 @OA\Property(property="category", type="string", example="Technology"),
     *                 @OA\Property(property="published_at", type="string", format="date-time", example="2024-12-01T10:00:00Z"),
     *                 @OA\Property(property="source", type="string", example="Website")
     *             )
     *         )
     *     ),
     *     security={{"sanctum":{}}}
     * )
     */
    public function getPersonalizedFeed(Request $request)
    {
        return $this->userPreferenceService->getPersonalizedNewsFeed($request->user());
    }
}
