<?php

declare(strict_types=1);

namespace App\Features\Content\Queries;

use App\Features\Content\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final readonly class PublishedArticles
{
    public function latest(int $perPage = 9, string $category = '', string $search = ''): LengthAwarePaginator
    {
        return Article::query()
            ->published()
            ->whereRaw('LENGTH(body) > 700')
            ->when($category !== '', fn ($query) => $query->where('category', $category))
            ->when($search !== '', function ($query) use ($search): void {
                $like = '%'.$search.'%';

                $query->where(function ($sub) use ($like): void {
                    $sub->where('title', 'like', $like)
                        ->orWhere('excerpt', 'like', $like)
                        ->orWhere('body', 'like', $like)
                        ->orWhere('category', 'like', $like);
                });
            })
            ->latest('published_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * @return Collection<int, string>
     */
    public function categories(): Collection
    {
        return Article::query()
            ->published()
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');
    }

    public function bySlug(string $slug): Article
    {
        return Article::query()->published()->where('slug', $slug)->firstOrFail();
    }
}
