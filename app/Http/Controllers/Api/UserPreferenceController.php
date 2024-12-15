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
     *         description="Preferences retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Preferences retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=4),
     *                     @OA\Property(property="user_id", type="integer", example=22),
     *                     @OA\Property(property="preference_type", type="string", example="source"),
     *                     @OA\Property(property="preference_value", type="string", example="BBC News"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-12-15T18:19:49.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-12-15T18:19:49.000000Z")
     *                 )
     *             ),
     *             @OA\Property(property="error", type="string", nullable=true, example=null),
     *             @OA\Property(property="code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal server error"),
     *             @OA\Property(property="error", type="string", example="Server error"),
     *             @OA\Property(property="code", type="integer", example=500)
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
     *         description="Preference saved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Preference saved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(),
     *                 example={}
     *             ),
     *             @OA\Property(property="error", type="string", nullable=true, example=null),
     *             @OA\Property(property="code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal server error"),
     *             @OA\Property(property="error", type="string", example="Server error"),
     *             @OA\Property(property="code", type="integer", example=500)
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
     *         description="Personalized news feed retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Personalized news feed retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=34),
     *                         @OA\Property(property="title", type="string", example="Tracing the privileged family roots of suspected killer Luigi Mangione in Baltimore - BBC.com"),
     *                         @OA\Property(property="content", type="string", example="This week, the surname Mangione became inextricably linked with the cold-blooded killing of a health-insurance executive in New York City, when 26-year-old Luigi Mangione was charged with his murder.… [+5494 chars]"),
     *                         @OA\Property(property="source", type="string", example="BBC News"),
     *                         @OA\Property(property="author", type="string", example="Unknown"),
     *                         @OA\Property(property="category", type="string", example="General"),
     *                         @OA\Property(property="published_at", type="string", format="date-time", example="2024-12-14 17:27:31"),
     *                         @OA\Property(property="url", type="string", format="url", example="https://www.bbc.com/news/articles/crl3jkjxp75o"),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2024-12-15T18:19:15.000000Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2024-12-15T18:19:15.000000Z")
     *                     )
     *                 ),
     *                 @OA\Property(property="first_page_url", type="string", format="url", example="http://localhost:8080/api/preferences/news-feed?page=1"),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=1),
     *                 @OA\Property(property="last_page_url", type="string", format="url", example="http://localhost:8080/api/preferences/news-feed?page=1"),
     *                 @OA\Property(
     *                     property="links",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="url", type="string", nullable=true, example=null),
     *                         @OA\Property(property="label", type="string", example="&laquo; Previous"),
     *                         @OA\Property(property="active", type="boolean", example=false)
     *                     )
     *                 ),
     *                 @OA\Property(property="next_page_url", type="string", nullable=true, example=null),
     *                 @OA\Property(property="path", type="string", format="url", example="http://localhost:8080/api/preferences/news-feed"),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="prev_page_url", type="string", nullable=true, example=null),
     *                 @OA\Property(property="to", type="integer", example=1),
     *                 @OA\Property(property="total", type="integer", example=1)
     *             ),
     *             @OA\Property(property="error", type="string", nullable=true, example=null),
     *             @OA\Property(property="code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal server error"),
     *             @OA\Property(property="error", type="string", example="Server error"),
     *             @OA\Property(property="code", type="integer", example=500)
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
