<?php

declare(strict_types=1);

namespace App\Features\Content\Queries;

use App\Features\Content\Api\ArticleSummary;
use App\Features\Content\Api\PublishesArticles;
use App\Features\Content\Models\Article;

final readonly class SearchArticles implements PublishesArticles
{
    /**
     * @return list<ArticleSummary>
     */
    public function search(string $query, int $limit = 8): array
    {
        $like = '%'.$query.'%';

        return Article::query()
            ->published()
            ->where(function ($sub) use ($like): void {
                $sub->where('title', 'like', $like)
                    ->orWhere('excerpt', 'like', $like)
                    ->orWhere('body', 'like', $like)
                    ->orWhere('category', 'like', $like);
            })
            ->latest('published_at')
            ->limit($limit)
            ->get()
            ->map(fn (Article $article): ArticleSummary => new ArticleSummary(
                id: $article->id,
                title: $article->title,
                slug: $article->slug,
                category: $article->category,
                excerpt: $article->excerpt,
                imageUrl: $article->displayImage(),
                publishedAt: $article->published_at?->toIso8601String(),
            ))
            ->all();
    }
}
