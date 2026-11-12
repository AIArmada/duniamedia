<?php

namespace Database\Seeders;

use App\Models\Service;
use Database\Seeders\Concerns\SeedsMedia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    use SeedsMedia;

    public function run(): void
    {
        $services = [
            ['Media Planning & Buying', 'Media Strategy', 'Strategy and placements across TV, radio, outdoor, print and digital.', 'tv'],
            ['Branding & Creative Studio', 'Creative', 'Identity systems, graphic design, campaign concepts, visual and audio assets.', 'digital'],
            ['AI Video & Image Production', 'AI & Technology', 'Concept-to-content workflows for image, film, music and creative production.', 'creator-feature'],
            ['Websites & Conversion Systems', 'Technology', 'Landing pages, websites, CRM, campaign tracking and workflow automation.', 'custom-media'],
            ['Creator & Influencer Campaigns', 'Creator Network', 'Niche creator matching, briefing, creative production and measurement.', 'creators'],
            ['Media Asset Monetisation', 'Custom Media', 'Turn appropriate premises and physical spaces into sellable advertising assets.', 'indoor'],
            ['AI Seminars & Corporate Training', 'Academy', 'Practical AI, content, productivity and transformation workshops.', 'training'],
            ['Campaign Analytics & Automation', 'Intelligence', 'Attribution, dashboards, lead handling and measurable marketing workflows.', 'digital'],
        ];

        foreach ($services as $index => [$name, $category, $summary, $image]) {
            $service = Service::updateOrCreate(
                ['service_code' => Str::slug($name)],
                [
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'category' => $category,
                    'summary' => $summary,
                    'description' => $summary,
                    'is_featured' => true,
                    'status' => 'published',
                    'published_at' => now(),
                    'sort_order' => $index,
                ],
            );

            $this->replaceMedia($service, 'assets/img/'.$image.'.webp', 'image');
        }
    }
}
