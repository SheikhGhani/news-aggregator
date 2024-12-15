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
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="The page number for pagination.",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of articles",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Articles retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="data", type="array", 
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="Sample Article Title"),
     *                         @OA\Property(property="content", type="string", example="This is a sample article content."),
     *                         @OA\Property(property="author", type="string", example="John Doe"),
     *                         @OA\Property(property="category", type="string", example="Technology"),
     *                         @OA\Property(property="published_at", type="string", format="date-time", example="2024-12-14 09:32:00"),
     *                         @OA\Property(property="source", type="string", example="Sample Source"),
     *                         @OA\Property(property="url", type="string", format="url", example="https://example.com/sample-article"),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2024-12-14T14:02:22.000000Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2024-12-14T14:02:22.000000Z")
     *                     )
     *                 ),
     *                 @OA\Property(property="first_page_url", type="string", format="url", example="http://127.0.0.1:8000/api/articles?page=1"),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=1),
     *                 @OA\Property(property="last_page_url", type="string", format="url", example="http://127.0.0.1:8000/api/articles?page=1"),
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
     *                 @OA\Property(property="path", type="string", format="url", example="http://127.0.0.1:8000/api/articles"),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="prev_page_url", type="string", nullable=true, example=null),
     *                 @OA\Property(property="to", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=10)
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
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Article retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=35),
     *                 @OA\Property(property="title", type="string", example="Isak Andic, founder of Mango fashion chain, dies in accident aged 71 - BBC.com"),
     *                 @OA\Property(property="content", type="string", example="N/A"),
     *                 @OA\Property(property="author", type="string", example="BBC.com"),
     *                 @OA\Property(property="category", type="string", example="General"),
     *                 @OA\Property(property="published_at", type="string", format="date-time", example="2024-12-14 17:27:00"),
     *                 @OA\Property(property="source", type="string", example="Google News"),
     *                 @OA\Property(property="url", type="string", format="url", example="https://news.google.com/rss/articles/CBMiWkFVX3lxTE9aOWlwQUZuOHQ0RERCOFhzRl84VXFGbHczNFRodk9vUlBxb2xNeEE1TnR0alRCelBzdE5YREVQbXBNVnhvSEhaSWR6VVVzUEVlYWlwN2o2NTZuUdIBX0FVX3lxTFBOZmsyNnVlalFDZDlZTGFPUjJuUlE1d2Q4eXEtaGU1eUM0Y2pXc2szOTAzUnVxWHU4OXV4SGtXZmRqNHR4TlR1d1lDZmlpYTB6WUVYNXlwVTRwM2N0MlpF?oc=5"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-12-15T18:19:15.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-12-15T18:19:15.000000Z")
     *             ),
     *             @OA\Property(property="error", type="string", nullable=true, example=null),
     *             @OA\Property(property="code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Article not found"),
     *             @OA\Property(property="error", type="string", example="null"),
     *             @OA\Property(property="code", type="integer", example=404)
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
    public function show($id)
    {
        return $this->articleService->getArticleById($id);
    }
}
