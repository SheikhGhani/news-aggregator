<?php

namespace App\Http\Services;

use App\Models\Article;


class ArticleService extends BaseService
{
   public function getArticles($request)
   {
       try {
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

            $articles = $query->paginate(10);

            return $this->sendSuccessResponseJson($articles, 'Articles retrieved successfully');
       } catch (\Exception $e) {
           return $this->sendErrorResponseJson('Failed to retrieve articles', [$e->getMessage()]);
       }
   }

   public function getArticleById($id)
   {
         try {
            $article = Article::find($id);

            if (!$article) {
                return $this->sendErrorResponseJson('Article not found', [], 404);
            }

            return $this->sendSuccessResponseJson($article, 'Article retrieved successfully');
         } catch (\Exception $e) {
              return $this->sendErrorResponseJson('Failed to retrieve article', [$e->getMessage()]);
         }
   }
}
