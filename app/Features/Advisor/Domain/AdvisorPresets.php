<?php

declare(strict_types=1);

namespace App\Features\Advisor\Domain;

final class AdvisorPresets
{
    /**
     * @return list<array{
     *     key: string, label: string, description: string, objective: string,
     *     budget_amount: float, budget_flexibility: string, regions: list<string>,
     *     languages: list<string>, timeline: string, launch_window: ?string,
     *     industry: ?string, audience: ?string, preferred_mediums: list<string>
     * }>
     */
    public static function all(): array
    {
        return [
            [
                'key' => 'festive-raya',
                'label' => 'Festive Raya push',
                'description' => 'National festive campaign with broadcast reach and a creator-led digital layer.',
                'objective' => 'festive',
                'budget_amount' => 120000.0,
                'budget_flexibility' => 'guided',
                'regions' => ['Nationwide'],
                'languages' => ['Malay', 'English'],
                'timeline' => 'within_four_weeks',
                'launch_window' => 'Hari Raya 2026',
                'industry' => 'FMCG',
                'audience' => 'Urban Malay families 25-45',
                'preferred_mediums' => ['TV & Broadcast', 'Digital', 'Creators'],
            ],
            [
                'key' => 'kl-launch',
                'label' => 'KL product launch',
                'description' => 'High-impact Klang Valley launch with premium outdoor and performance retargeting.',
                'objective' => 'launch',
                'budget_amount' => 90000.0,
                'budget_flexibility' => 'flexible',
                'regions' => ['Klang Valley', 'Airports & Transit'],
                'languages' => ['English', 'Chinese', 'Malay'],
                'timeline' => 'one_to_three_months',
                'launch_window' => 'Q2 2026',
                'industry' => 'Technology',
                'audience' => 'Urban professionals and early adopters',
                'preferred_mediums' => ['Outdoor', 'Indoor', 'Digital'],
            ],
            [
                'key' => 'lead-engine',
                'label' => 'Lead generation engine',
                'description' => 'Always-on performance engine combining digital, radio and conversion systems.',
                'objective' => 'leads',
                'budget_amount' => 30000.0,
                'budget_flexibility' => 'strict',
                'regions' => ['Klang Valley', 'Nationwide'],
                'languages' => ['English', 'Malay'],
                'timeline' => 'ongoing',
                'launch_window' => null,
                'industry' => 'Education',
                'audience' => 'Working adults researching part-time study',
                'preferred_mediums' => ['Digital', 'Radio'],
            ],
            [
                'key' => 'east-malaysia',
                'label' => 'East Malaysia reach',
                'description' => 'Sabah and Sarawak audience build with local radio, print and creator partnerships.',
                'objective' => 'awareness',
                'budget_amount' => 45000.0,
                'budget_flexibility' => 'guided',
                'regions' => ['Sabah & Sarawak'],
                'languages' => ['Malay', 'Chinese', 'English'],
                'timeline' => 'three_to_six_months',
                'launch_window' => 'Gawai season',
                'industry' => 'Retail',
                'audience' => 'Kota Kinabalu and Kuching households',
                'preferred_mediums' => ['Radio', 'Print', 'Creators'],
            ],
        ];
    }
}
