<?php

declare(strict_types=1);

namespace App\Features\Creators\EntryPoints\Http;

use App\Features\Creators\Models\CreatorProfile;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

final class CreatorProfileController
{
    public function __invoke(string $slug): View
    {
        $creator = CreatorProfile::query()
            ->publiclyListed()
            ->with(['media', 'accounts'])
            ->where('slug', $slug)
            ->firstOrFail();

        $name = $creator->displayName();
        $account = $creator->accounts
            ->first(fn ($account): bool => ltrim((string) $account->handle, '@') === ltrim((string) $creator->handle, '@'));
        $handle = ltrim((string) $creator->handle, '@');
        $socialUrl = null;

        if (preg_match('/\A[a-zA-Z0-9_.-]{1,100}\z/', $handle) === 1) {
            $socialUrl = match (strtolower((string) $creator->social_platform)) {
                'tiktok' => 'https://www.tiktok.com/@'.$handle,
                'instagram' => 'https://www.instagram.com/'.$handle.'/',
                'youtube' => 'https://www.youtube.com/@'.$handle,
                default => null,
            };
        }

        $editorial = (require resource_path('data/creator-profiles.php'))[$slug] ?? [];
        $metrics = [];

        if ($account && $account->metrics_as_of !== null) {
            foreach ([
                'Followers' => $account->followers,
                'Average views' => $account->average_views,
                'Engagement rate' => $account->engagement_rate === null ? null : number_format((float) $account->engagement_rate, 2).'%',
            ] as $label => $value) {
                if ($value !== null) {
                    $metrics[$label] = is_int($value) ? number_format($value) : $value;
                }
            }
        }

        return view('pages.creator-profile', [
            'creator' => $creator,
            'name' => $name,
            'handle' => $handle,
            'socialUrl' => $socialUrl,
            'portrait' => $creator->getFirstMediaUrl('image'),
            'gallery' => $creator->getMedia('gallery')->take(6),
            'editorial' => $editorial,
            'metrics' => $metrics,
            'metricsDate' => $account?->metrics_as_of,
            'enquiryUrl' => route('contact', ['creator' => $creator->slug]).'#enquiry',
            'pageTitle' => $name.' — Creator Profile | Dunia Media',
            'metaDescription' => Str::limit(strip_tags((string) ($creator->bio ?: 'Discover '.$name.' on the Dunia Media creator network.')), 155),
        ]);
    }
}
