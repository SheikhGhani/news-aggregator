<?php

namespace App\Http\Services;

use App\Models\Article;
use Illuminate\Support\Facades\Cache;

class ArticleService extends BaseService
{
   public function getArticles($request)
   {
       try {
            $cacheKey = 'articles_' . md5(json_encode($request->all()));
            if (Cache::has($cacheKey)) {
                $cachedData = Cache::get($cacheKey); // Retrieve cached data
                \Log::info("Cache hit for key: {$cacheKey}");
                \Log::info("Cached result: " . json_encode($cachedData));
            } else {
                \Log::info("Cache miss for key: {$cacheKey}");
            }
            $articles = Cache::remember($cacheKey, 600, function () use ($request) {
                $query = Article::query();
    
                if ($request->has('keyword')) {
                    $query->where('title', 'like', '%' . $request->input('keyword') . '%')
                        ->orWhere('content', 'like', '%' . $request->input('keyword') . '%');
                }
    
                if ($request->has('date_from') && $request->has('date_to')) {
                    $query->whereBetween('published_at', [$request->input('date_from'), $request->input('date_to')]);
                }
    
                if ($request->has('category')) {
                    $query->where('category', $request->input('category'));
                }
    
                if ($request->has('source')) {
                    $query->where('source', $request->input('source'));
                }
    
                return $query->paginate(10); // Fetch paginated results
            });

            return $this->sendSuccessResponseJson($articles, 'Articles retrieved successfully');
       } catch (\Exception $e) {
           return $this->sendErrorResponseJson('Failed to retrieve articles', [$e->getMessage()]);
       }
   }

   public function getArticleById($id)
   {
         try {
            $cacheKey = "article_{$id}";
            $article = Cache::remember($cacheKey, 600, function () use ($id) {
                return Article::find($id);
            });

            if (!$article) {
                return $this->sendErrorResponseJson('Article not found', [], 404);
            }

            return $this->sendSuccessResponseJson($article, 'Article retrieved successfully');
         } catch (\Exception $e) {
              return $this->sendErrorResponseJson('Failed to retrieve article', [$e->getMessage()]);
         }
   }
}
