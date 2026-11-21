<?php

declare(strict_types=1);

namespace App\Features\Content\EntryPoints\Http;

use App\Features\Content\Queries\PublishedArticles;
use Illuminate\Http\Request;
use Illuminate\View\View;

final readonly class InsightsController
{
    public function __construct(private PublishedArticles $articles) {}

    public function __invoke(Request $request): View
    {
        $category = trim((string) $request->query('category', ''));
        $search = trim((string) $request->query('q', ''));

        return view('pages.insights', [
            'articles' => $this->articles->latest(9, $category, $search),
            'categories' => $this->articles->categories(),
            'category' => $category,
            'search' => $search,
            'pageTitle' => 'Insights & Ideas | Dunia Media',
            'metaDescription' => 'Practical perspectives on media, creators, AI and measurable campaigns from Dunia Media and RA Media Relations.',
        ]);
    }
}
