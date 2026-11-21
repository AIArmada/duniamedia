<?php

declare(strict_types=1);

namespace App\Features\Content\Api;

final readonly class ArticleSummary
{
    public function __construct(
        public string $id,
        public string $title,
        public string $slug,
        public ?string $category,
        public string $excerpt,
        public string $imageUrl,
        public ?string $publishedAt,
    ) {}
}
