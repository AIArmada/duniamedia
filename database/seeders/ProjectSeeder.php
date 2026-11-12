<?php

namespace Database\Seeders;

use App\Models\Project;
use Database\Seeders\Concerns\SeedsMedia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    use SeedsMedia;

    public function run(): void
    {
        foreach ([
            ['Integrated City Awareness Concept', 'Outdoor', 'A conceptual multi-touchpoint city awareness campaign.', 'outdoor'],
            ['Creator-Led Product Launch Concept', 'Creators', 'A demonstration of targeted creator collaboration and conversion journeys.', 'creators'],
            ['Smart Media Conversion Concept', 'Digital', 'An example of connecting broadcast attention to a measurable enquiry funnel.', 'digital'],
        ] as $index => [$name, $category, $summary, $image]) {
            $project = Project::updateOrCreate(
                ['project_code' => 'concept-'.Str::slug($name)],
                [
                    'client_party_id' => null,
                    'project_name' => $name,
                    'slug' => Str::slug($name),
                    'category' => $category,
                    'summary' => $summary,
                    'brief' => $summary,
                    'status' => 'concept',
                    'sort_order' => $index,
                    'published_at' => now(),
                ],
            );

            $this->replaceMedia($project, 'assets/img/'.$image.'.webp', 'image');
        }
    }
}
