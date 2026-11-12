<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Training;
use Database\Seeders\Concerns\SeedsMedia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    use SeedsMedia;

    public function run(): void
    {
        foreach ([
            ['How to Plan a Better Media Mix', 'Strategy', 'Start with the audience, outcome and measurement plan before choosing channels.', 'editorial-media-mix'],
            ['Why Niche Creators Matter', 'Creator Economy', 'The right creator is not always the one with the most followers.', 'editorial-creators'],
            ['From Awareness to Enquiries', 'Measurement', 'Link offline and online channels to appropriate conversion points.', 'editorial-measurement'],
        ] as $article) {
            [$title, $category, $excerpt, $image] = $article;

            $article = Article::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'category' => $category,
                    'excerpt' => $excerpt,
                    'body' => $excerpt."\n\nWork backwards from commercial outcomes. Define the audience and communications objective, design useful creative, select suitable distribution channels, and use a practical measurement plan. A campaign is a learning process, not a guarantee of sales.",
                    'status' => 'published',
                    'published_at' => now(),
                ],
            );

            $this->replaceMedia($article, 'assets/img/'.$image.'.webp', 'image');
        }

        foreach ([
            ['AI for Business & Marketing', 'AI', 'Practical AI workflows for content, research and business operations.'],
            ['Creative Video Production', 'Production', 'Plan, generate, edit and distribute creative video content.'],
            ['Digital Marketing & Conversion', 'Marketing', 'Design campaigns with measurable calls to action and customer journeys.'],
        ] as $training) {
            [$title, $category, $summary] = $training;

            $training = Training::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'category' => $category,
                    'summary' => $summary,
                    'description' => $summary,
                    'format' => 'Corporate / Workshop',
                    'duration' => 'Customisable',
                    'status' => 'published',
                    'published_at' => now(),
                ],
            );

            $this->replaceMedia($training, 'assets/img/training.webp', 'image');
        }
    }
}
