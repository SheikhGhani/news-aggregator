<?php

namespace App\Http\Controllers\Api;

use App\Http\Services\ArticleService;
use App\Models\Article;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Illuminate\Routing\Controller as BaseController;


class ArticleController extends BaseController
{
    protected $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }
    /**
     * @OA\Get(
     *     path="/api/articles",
     *     tags={"Articles"},
     *     summary="Fetch a list of articles with pagination, search, and filtering.",
     *     description="Fetch articles by keyword, date range, category, and source with pagination support.",
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="Search articles by keyword in title or content.",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="date_from",
     *         in="query",
     *         description="Start date for filtering articles (YYYY-MM-DD).",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="date_to",
     *         in="query",
     *         description="End date for filtering articles (YYYY-MM-DD).",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Filter by article category.",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="source",
     *         in="query",
     *         description="Filter by article source.",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of articles",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="data", type="array", 
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Article Title"),
     *                     @OA\Property(property="content", type="string", example="Article content here."),
     *                     @OA\Property(property="author", type="string", example="John Doe"),
     *                     @OA\Property(property="category", type="string", example="Technology"),
     *                     @OA\Property(property="published_at", type="string", format="date-time", example="2024-12-01T10:00:00Z"),
     *                     @OA\Property(property="source", type="string", example="Website")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Invalid parameters")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Server error")
     *         )
     *     ),
     *    security={{"sanctum":{}}}
     * )
     */
    public function index(Request $request)
    {
        return $this->articleService->getArticles($request);
    }

    /**
     * @OA\Get(
     *     path="/api/articles/{id}",
     *     tags={"Articles"},
     *     summary="Fetch a specific article by ID",
     *     description="Get details of a specific article by its ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the article to retrieve.",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Article Title"),
     *             @OA\Property(property="content", type="string", example="Detailed content here."),
     *             @OA\Property(property="author", type="string", example="John Doe"),
     *             @OA\Property(property="category", type="string", example="Technology"),
     *             @OA\Property(property="published_at", type="string", format="date-time", example="2024-12-01T10:00:00Z"),
     *             @OA\Property(property="source", type="string", example="Website")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Article not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Server error")
     *         )
     *     ),
     *    security={{"sanctum":{}}}
     * )
     */
    public function show($id)
    {
        return $this->articleService->getArticleById($id);
    }
}
