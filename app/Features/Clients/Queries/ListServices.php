<?php

declare(strict_types=1);

namespace App\Features\Clients\Queries;

use App\Features\Clients\Api\ListsServices;
use App\Features\Clients\Api\ServiceSummary;
use App\Features\Clients\Models\Service;

final readonly class ListServices implements ListsServices
{
    /**
     * @return list<ServiceSummary>
     */
    public function published(): array
    {
        return Service::query()
            ->published()
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(fn (Service $service): ServiceSummary => new ServiceSummary(
                id: $service->id,
                slug: $service->slug,
                name: $service->name,
                category: $service->category,
                summary: $service->summary,
            ))
            ->all();
    }

    /**
     * @return array<string, string>
     */
    public function options(): array
    {
        return Service::query()
            ->published()
            ->orderBy('name')
            ->pluck('name', 'slug')
            ->all();
    }
}
