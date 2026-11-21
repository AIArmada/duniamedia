<?php

declare(strict_types=1);

namespace App\Features\Content\Api;

interface PublishesArticles
{
    /**
     * Published articles matching a free-text search, newest first.
     *
     * @return list<ArticleSummary>
     */
    public function search(string $query, int $limit = 8): array;
}
