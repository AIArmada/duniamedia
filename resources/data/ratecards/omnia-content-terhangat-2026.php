<?php

return [
    'eyebrow' => 'Rate card · 2026',
    'title' => 'Content Terhangat Campaign Planner',
    'subtitle' => 'Media Prima Omnia’s integrated TV, social and content packages. Choose a tier, then tailor the mix to your industry and language market.',
    'validity' => 'Campaigns run 1 Nov 2025 – 31 Oct 2026',
    'notes' => [
        'Prices exclude 8% SST. Production is not included.',
        'Packages run in consecutive weeks/months from the start date.',
        'Event bonus media value is redeemed during the event week.',
    ],
    'packages' => [
        'bronze' => ['name' => 'Bronze', 'price' => 200000, 'months' => 3, 'bonus' => 20000, 'total' => 220000],
        'silver' => ['name' => 'Silver', 'price' => 350000, 'months' => 6, 'bonus' => 42000, 'total' => 392000],
        'gold' => ['name' => 'Gold', 'price' => 500000, 'months' => 9, 'bonus' => 70000, 'total' => 570000],
        'platinum' => ['name' => 'Platinum', 'price' => 800000, 'months' => 12, 'bonus' => 160000, 'total' => 960000],
    ],
    'segments' => [
        [
            'key' => 'beauty-ms', 'industry' => 'Beauty & Wellness', 'language' => 'Malay', 'network' => 'TV3 + TV9',
            'tiers' => [
                'bronze' => ['TV3: 75 × 15s spots (25/week)', 'TV9: 50 × 15s spots (25/week)', 'TV3 social: 2 × 3 video posts'],
                'silver' => ['TV3: 125 × 15s spots (25/week)', 'TV3 social: 2 × 3 video posts', 'TV3 Muzik Muzik integration: 1 × 2 min', 'TV3 WHI interview: 1 × 6 min'],
                'gold' => ['TV3: 100 × 15s spots', 'TV9: 75 × 15s spots', 'TV3 social: 2 × 3 video posts', 'TV3 Melodi integration: 1 × 6 min', 'TV3 WHI interview: 1 × 6 min', 'TV3 Morning Drama sponsorship: 13 eps'],
                'platinum' => ['TV3: 125 × 15s spots', 'TV3 social: 2 × 3 video posts', 'TV3 Melodi integration: 1 × 6 min', 'TV3 Night Drama sponsorship: 13 eps', 'TV3 Buletin 1.30 coverage: 2 × 2 min', 'TV3 Nightline coverage: 2 × 2 min'],
            ],
        ],
        [
            'key' => 'beauty-zh', 'industry' => 'Beauty & Wellness', 'language' => 'Chinese', 'network' => '8TV',
            'tiers' => [
                'bronze' => ['8TV: 240 × 15s spots (30/week)', '8TV social: 2 × 3 video posts'],
                'silver' => ['8TV: 420 × 15s spots', '8TV bonus: 7 × 15s spots', '8TV social: 2 × 3 video posts', '8 e-News: 1 × 2 min', '8TV Living Delight: 1 × 6 min'],
                'gold' => ['8TV: 300 × 15s spots', '8TV bonus: 7 × 15s spots', '8TV social: 4 × 3 video posts', '8 e-News: 2 × 2 min', '8TV Morning Drama: 13 eps', '8TV Afternoon Drama: 13 eps'],
                'platinum' => ['8TV: 600 × 15s spots', '8TV social: 2 × 3 video posts', '8 e-News: 2 × 2 min', '8TV Prime Drama: 13 eps', '8TV Midday Mandarin News: 1 × 2 min', '8TV Prime Mandarin News: 1 × 2 min'],
            ],
        ],
        [
            'key' => 'education-ms', 'industry' => 'Education', 'language' => 'Malay', 'network' => 'TV3 + TV9',
            'tiers' => [
                'bronze' => ['TV3: 75 × 15s spots', 'TV9: 50 × 15s spots', 'TV3 social: 2 × 3 video posts'],
                'silver' => ['TV3: 125 × 15s spots', 'TV9: 25 × 15s spots', 'TV3 social: 2 × 3 video posts', 'TV3 Mbuzz integration: 1 × 2 min'],
                'gold' => ['TV3: 100 × 15s spots', 'TV9: 75 × 15s spots', 'TV3 social: 5 × 3 video posts', 'TV3 Melodi integration: 1 × 6 min', 'TV3 Mbuzz integration: 1 × 2 min'],
                'platinum' => ['TV3: 200 × 15s spots', 'TV9: 75 × 15s spots', 'TV3 social: 5 × 3 video posts', 'TV3 Mbuzz integration: 1 × 2 min', 'TV3 Melodi integration: 1 × 6 min', 'TV3 Buletin Utama + digital: 2 × 2 min'],
            ],
        ],
        [
            'key' => 'education-zh', 'industry' => 'Education', 'language' => 'Chinese', 'network' => '8TV',
            'tiers' => [
                'bronze' => ['8TV: 240 × 15s spots', '8TV social: 2 × 3 video posts'],
                'silver' => ['8TV: 420 × 15s spots', '8TV bonus: 7 × 15s spots', '8TV social: 2 × 3 video posts', '8 e-News: 1 × 2 min', '8TV Living Delight: 1 × 6 min'],
                'gold' => ['8TV: 360 × 15s spots', '8TV bonus: 7 × 15s spots', '8TV social: 4 × 3 video posts', '8 e-News: 3 × 2 min', '8TV Living Delight: 1 × 6 min', '8TV Afternoon Drama: 13 eps'],
                'platinum' => ['8TV: 480 × 15s spots', '8TV social: 6 × 3 video posts', '8 e-News: 2 × 2 min', '8TV Money Matters / Shall We Talk: 1 × 6 min', '8TV Prime Drama: 13 eps', '8TV Midday Mandarin News: 1 × 2 min'],
            ],
        ],
        [
            'key' => 'financial-ms', 'industry' => 'Financial Services', 'language' => 'Malay', 'network' => 'TV3 + TV9',
            'tiers' => [
                'bronze' => ['TV3: 75 × 15s spots', 'TV9: 50 × 15s spots', 'TV3 social: 2 × 3 video posts'],
                'silver' => ['TV3: 100 × 15s spots', 'TV3 social: 3 × 3 video posts', 'TV3 MHI interview: 1 × 6 min', 'TV3 Money Matters / Women Talk: 2 × 6 min', 'TV9 NLKO interview: 2 × 6 min'],
                'gold' => ['TV3: 100 × 15s spots', 'TV3 social: 2 × 3 video posts', 'TV3 MHI interview: 1 × 6 min', 'TV3 Money Matters / Women Talk: 3 × 6 min', 'TV3 Prime Movie sponsorship: 8 eps'],
                'platinum' => ['TV3: 150 × 15s spots', 'TV9: 25 × 15s spots', 'TV3 social: 4 × 3 video posts', 'TV3 Mbuzz integration: 1 × 2 min', 'TV3 Money Matters / Women Talk: 3 × 6 min', 'TV9 NLKO interview: 2 × 6 min', 'TV3 Prime Movie: 8 eps', 'TV3 Buletin Utama + digital: 1 × 2 min', 'TV3 Nightline + TV9 Berita coverage'],
            ],
        ],
        [
            'key' => 'financial-zh', 'industry' => 'Financial Services', 'language' => 'Chinese', 'network' => '8TV',
            'tiers' => [
                'bronze' => ['8TV: 240 × 15s spots', '8TV social: 2 × 3 video posts'],
                'silver' => ['8TV: 360 × 15s spots', '8TV social: 2 × 3 video posts', '8 e-News: 2 × 2 min', '8TV Money Matters / Shall We Talk: 2 × 6 min'],
                'gold' => ['8TV: 360 × 15s spots', '8TV social: 2 × 3 video posts', '8 e-News: 4 × 2 min', '8TV Money Matters / Shall We Talk: 2 × 6 min', '8TV Afternoon Drama: 13 eps'],
                'platinum' => ['8TV: 360 × 15s spots', '8TV social: 4 × 3 video posts', '8 e-News: 2 × 2 min', '8TV Money Matters / Shall We Talk: 4 × 6 min', '8TV Prime Drama: 13 eps', '8TV Morning Express + Prime Mandarin News'],
            ],
        ],
        [
            'key' => 'fmcg-ms', 'industry' => 'FMCG', 'language' => 'Malay', 'network' => 'TV3 + TV9',
            'tiers' => [
                'bronze' => ['TV3: 75 × 15s spots', 'TV9: 50 × 15s spots', 'TV3 social: 2 × 3 video posts'],
                'silver' => ['TV3: 100 × 15s spots', 'TV9: 25 × 15s spots', 'TV3 social: 1 × 3 video posts', 'TV3 MHI: 2 × 6 min', 'TV3 WHI: 1 × 6 min', 'TV3 Helo Doktor: 1 × 6 min', 'TV9 NLKO: 2 × 6 min'],
                'gold' => ['TV3: 100 × 15s spots', 'TV9: 25 × 15s spots', 'TV3 social: 1 × 3 video posts', 'TV3 Melodi: 1 × 6 min', 'TV3 MHI: 1 × 6 min', 'TV3 Morning Drama: 13 eps', 'TV3 Cookery: 4 eps'],
                'platinum' => ['TV3: 125 × 15s spots', 'TV9: 25 × 15s spots', 'TV3 social: 2 × 3 video posts', 'TV3 Melodi: 1 × 6 min', 'TV3 MHI + WHI interviews', 'TV3 Morning + TV9 Prime Drama', 'TV3 Cookery: 4 eps', 'TV3 Buletin Utama + digital: 1 × 2 min'],
            ],
        ],
        [
            'key' => 'fmcg-zh', 'industry' => 'FMCG', 'language' => 'Chinese', 'network' => '8TV',
            'tiers' => [
                'bronze' => ['8TV: 240 × 15s spots', '8TV social: 2 × 3 video posts'],
                'silver' => ['8TV: 300 × 15s spots', '8TV bonus: 14 × 15s spots', '8TV social: 4 × 3 video posts', '8TV Living Delight: 4 × 6 min'],
                'gold' => ['8TV: 270 × 15s spots', '8TV social: 1 × 3 video posts', '8 e-News: 1 × 2 min', '8TV Living Delight: 2 × 6 min', '8TV Afternoon Drama: 13 eps', '8TV Night Drama: 13 eps'],
                'platinum' => ['8TV: 300 × 15s spots', '8TV bonus: 28 × 15s spots', '8TV social: 1 × 3 video posts', '8 e-News: 2 × 2 min', '8TV Living Delight: 2 × 6 min', '8TV Afternoon + Prime Drama', '8TV Morning Express + Midday News'],
            ],
        ],
        [
            'key' => 'fnb-ms', 'industry' => 'F&B', 'language' => 'Malay', 'network' => 'TV3 + TV9',
            'tiers' => [
                'bronze' => ['TV3: 75 × 15s spots', 'TV9: 50 × 15s spots', 'TV3 social: 2 × 3 video posts'],
                'silver' => ['TV3: 100 × 15s spots', 'TV3 social: 3 × 3 video posts', 'TV3 Muzik Muzik: 2 × 2 min', 'TV3 MHI: 1 × 6 min', 'TV9 NLKO: 2 × 6 min'],
                'gold' => ['TV3: 150 × 15s spots', 'TV9: 50 × 15s spots', 'TV3 social: 3 × 3 video posts', 'TV3 Muzik Muzik: 2 × 2 min', 'TV3 MHI: 1 × 6 min', 'TV9 NLKO: 2 × 6 min', 'TV3 Cookery: 4 eps'],
                'platinum' => ['TV3: 150 × 15s spots', 'TV9: 50 × 15s spots', 'TV3 social: 1 × 3 video posts', 'TV3 MHI: 1 × 6 min', 'TV9 NLKO: 2 × 6 min', 'TV3 Night Drama: 13 eps', 'TV3 Cookery: 2 × 4 eps'],
            ],
        ],
        [
            'key' => 'fnb-zh', 'industry' => 'F&B', 'language' => 'Chinese', 'network' => '8TV',
            'tiers' => [
                'bronze' => ['8TV: 150 × 15s spots', '8TV social: 5 × 3 video posts'],
                'silver' => ['8TV: 420 × 15s spots', '8TV bonus: 14 × 15s spots', '8TV social: 2 × 3 video posts', '8 e-News: 2 × 2 min'],
                'gold' => ['8TV: 360 × 15s spots', '8TV social: 5 × 3 video posts', '8TV Living Delight: 2 × 6 min', '8TV Night Drama: 13 eps'],
                'platinum' => ['8TV: 360 × 15s spots', '8TV bonus: 7 × 15s spots', '8TV social: 10 × 3 video posts', '8TV Living Delight: 2 × 6 min', '8TV Prime Drama: 13 eps', '8TV Prime Mandarin News: 1 × 2 min'],
            ],
        ],
        [
            'key' => 'travel-ms', 'industry' => 'Travel & Leisure', 'language' => 'Malay', 'network' => 'TV3 + TV9',
            'tiers' => [
                'bronze' => ['TV3: 75 × 15s spots', 'TV9: 50 × 15s spots', 'TV3 social: 2 × 3 video posts'],
                'silver' => ['TV3: 100 × 15s spots', 'TV9: 50 × 15s spots', 'TV3 social: 2 × 3 video posts', 'TV3 WHI: 1 × 6 min', 'TV9 NLKO: 2 × 6 min'],
                'gold' => ['TV3: 150 × 15s spots', 'TV3 social: 5 × 3 video posts', 'TV3 MHI: 2 × 6 min', 'TV3 WHI: 1 × 6 min', 'TV9 NLKO: 2 × 6 min'],
                'platinum' => ['TV3: 200 × 15s spots', 'TV9: 25 × 15s spots', 'TV3 social: 6 × 3 video posts', 'TV3 Mbuzz: 1 × 2 min', 'TV3 Melodi: 1 × 6 min', 'TV3 WHI: 1 × 6 min', 'TV9 NLKO: 4 × 6 min', 'TV3 Buletin Utama + digital: 1 × 2 min'],
            ],
        ],
        [
            'key' => 'travel-zh', 'industry' => 'Travel & Leisure', 'language' => 'Chinese', 'network' => '8TV',
            'tiers' => [
                'bronze' => ['8TV: 180 × 15s spots', '8TV social: 4 × 3 video posts'],
                'silver' => ['8TV: 420 × 15s spots', '8TV bonus: 7 × 15s spots', '8TV social: 2 × 3 video posts', '8 e-News: 1 × 2 min', '8TV Living Delight: 1 × 6 min'],
                'gold' => ['8TV: 360 × 15s spots', '8TV social: 4 × 3 video posts', '8 e-News: 2 × 2 min', '8TV Living Delight: 2 × 6 min', '8TV Afternoon Drama: 13 eps'],
                'platinum' => ['8TV: 480 × 15s spots', '8TV bonus: 7 × 15s spots', '8TV social: 2 × 3 video posts', '8 e-News: 1 × 2 min', '8TV Living Delight: 2 × 6 min', '8TV Prime Drama: 13 eps', '8TV Midday + Prime Mandarin News'],
            ],
        ],
        [
            'key' => 'generic-ms', 'industry' => 'Generic', 'language' => 'Malay', 'network' => 'TV3 + TV9',
            'tiers' => [
                'bronze' => ['TV3: 100 × 15s spots (25/week)'],
                'silver' => ['TV3: 100 × 15s spots', 'TV3 social: 3 × 3 video posts', 'TV3 MHI: 1 × 6 min', 'TV3 WHI: 1 × 6 min', 'TV9 NLKO: 4 × 6 min'],
                'gold' => ['TV3: 100 × 15s spots', 'TV9: 50 × 15s spots', 'TV3 social: 2 × 3 video posts', 'TV3 WHI: 1 × 6 min', 'TV9 NLKO: 2 × 6 min', 'TV9 Prime Movie: 8 eps', 'TV9 Prime Drama: 13 eps'],
                'platinum' => ['TV3: 150 × 15s spots', 'TV9: 25 × 15s spots', 'TV3 social: 3 × 3 video posts', 'TV3 Melodi: 1 × 6 min', 'TV9 NLKO: 4 × 6 min', 'TV3 Prime Movie: 8 eps', 'TV9 Prime Movie: 8 eps', 'TV9 Afternoon Drama: 13 eps', 'TV3 Nightline + TV9 Berita coverage'],
            ],
        ],
        [
            'key' => 'generic-zh', 'industry' => 'Generic', 'language' => 'Chinese', 'network' => '8TV',
            'tiers' => [
                'bronze' => ['8TV: 300 × 15s spots (30/week)'],
                'silver' => ['8TV: 390 × 15s spots', '8TV bonus: 21 × 15s spots', '8 e-News: 2 × 2 min', '8TV Living Delight: 2 × 6 min', '8TV Money Matters / Shall We Talk: 1 × 6 min'],
                'gold' => ['8TV: 450 × 15s spots', '8TV bonus: 7 × 15s spots', '8TV Morning Drama: 13 eps', '8TV Night Drama: 13 eps'],
                'platinum' => ['8TV: 660 × 15s spots', '8TV bonus: 14 × 15s spots', '8TV Morning Drama: 13 eps', '8TV Night Drama: 2 × 13 eps', '8TV Prime Mandarin News: 1 × 2 min'],
            ],
        ],
    ],
    'alacarte' => [
        ['TV3 ROS', '15s', '25 spots', 50000],
        ['TV9 ROS', '15s', '25 spots', 5000],
        ['TV3 social post', '30–60s', '1 post', 20000],
        ['TV9 social post', '30–60s', '1 post', 15000],
        ['TV3 Muzik Muzik', '2 min', '1', 25000],
        ['TV3 Melodi (live + repeat)', '6 min', '1', 80000],
        ['TV3 MHI interview', '6 min', '1', 25000],
        ['TV3 WHI interview', '6 min', '1', 35000],
        ['TV9 NLKO interview', '6 min', '2', 15000],
        ['TV3 Buletin Utama + digital', '2 min + YT/FB', '1', 75000],
        ['8TV ROS', '15s', '30 spots', 20000],
        ['8TV social post', '30–60s', '1', 20000],
        ['8TV Living Delight', '6 min', '1', 15000],
        ['8TV Prime Drama', '13 eps', '1', 300000],
    ],
    'terms' => [
        'Eligibility: advertisers who spent under RM250,000 on Media Prima TV via Omnia, or lapsed since July 2024.',
        'Packages must be used between 1 November 2025 and 31 October 2026, in consecutive months.',
        'Blackout dates: 8TV 9–22 Feb 2026; TV3/TV9 2–22 Mar 2026. ROS placements can run through blackout with 20% loading.',
        'Full payment by 30 November 2025 unlocks an extra RM100,000 media value.',
        'Production costs are separate; all prices exclude 8% SST.',
        'Unused packages are forfeited after 31 October 2026. Packages cannot be cancelled or transferred.',
    ],
];
