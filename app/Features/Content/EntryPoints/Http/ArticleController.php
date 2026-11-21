<?php

declare(strict_types=1);

namespace App\Features\Content\EntryPoints\Http;

use App\Features\Content\Queries\PublishedArticles;
use Illuminate\Support\Str;
use Illuminate\View\View;

final readonly class ArticleController
{
    public function __construct(private PublishedArticles $articles) {}

    public function __invoke(string $slug): View
    {
        $article = $this->articles->bySlug($slug);

        return view('pages.article', [
            'article' => $article,
            'pageTitle' => $article->title.' | Dunia Media Insights',
            'metaDescription' => Str::limit(strip_tags($article->excerpt), 155),
        ]);
    }
}
