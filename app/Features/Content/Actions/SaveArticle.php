<?php

declare(strict_types=1);

namespace App\Features\Content\Actions;

use App\Features\Content\Models\Article;
use Illuminate\Support\Arr;

final readonly class SaveArticle
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(array $attributes, ?Article $article = null): Article
    {
        $article ??= new Article;
        $article->fill(Arr::except($attributes, ['id', 'created_at', 'updated_at']));
        $article->save();

        return $article;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): Article
    {
        return Article::query()->updateOrCreate($keys, $attributes);
    }
}
