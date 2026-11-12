<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\CaseStudy;
use App\Models\CaseStudyCampaign;
use App\Models\CaseStudyProject;
use App\Models\ClientReference;
use App\Models\Organization;
use App\Models\Party;
use App\Models\PartyRole;
use App\Models\Project;
use App\Models\ProjectService;
use App\Models\Service;
use Carbon\Carbon;
use Database\Seeders\Concerns\SeedsMedia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File as FileFacade;
use Illuminate\Support\Str;

class ClientWorkSeeder extends Seeder
{
    use SeedsMedia;

    public function run(): void
    {
        $path = resource_path('data/client-work.json');

        if (! FileFacade::exists($path)) {
            return;
        }

        $data = json_decode(FileFacade::get($path), true);
        $raMedia = Party::where('slug', 'ra-media-relations')->firstOrFail();

        $mediaByKey = collect($data['media'] ?? [])->keyBy('key');
        $parties = [];
        $projects = [];
        $years = [];

        foreach ($data['clients'] ?? [] as $client) {
            $party = Party::updateOrCreate(
                ['slug' => $client['slug']],
                [
                    'party_type' => 'organization',
                    'display_name' => $client['name'],
                    'legal_name' => $client['name'],
                    'status' => 'active',
                    'notes' => $client['summary'] ?? null,
                ],
            );

            $this->replaceMedia($party, $client['logo'] ?? null, 'logo');

            Organization::updateOrCreate(
                ['party_id' => $party->id],
                [
                    'industry' => $client['industry'] ?? null,
                    'organization_type' => $client['sector'] ?? 'business',
                ],
            );

            PartyRole::updateOrCreate(
                ['party_id' => $party->id, 'role_code' => 'client'],
                ['valid_from' => now()->startOfYear()],
            );

            ClientReference::updateOrCreate(
                ['client_party_id' => $party->id, 'reference_type' => 'client_logo'],
                [
                    'permission_granted' => true,
                    'public_label' => $client['short_name'] ?? $client['name'],
                ],
            );

            $parties[$client['slug']] = $party;
            $years[$client['slug']] = [];
        }

        foreach ($data['campaigns'] ?? [] as $campaign) {
            $years[$campaign['client']][] = (int) $campaign['year'];
        }

        foreach ($data['clients'] ?? [] as $client) {
            $clientYears = $years[$client['slug']] ?? [];

            if ($clientYears === []) {
                $clientYears = [now()->year];
            }

            $projects[$client['slug']] = Project::updateOrCreate(
                ['project_code' => 'work-'.$client['slug']],
                [
                    'client_party_id' => $parties[$client['slug']]->id,
                    'project_name' => $client['name'].' work archive',
                    'slug' => 'work-'.$client['slug'],
                    'category' => $client['sector'] ?? null,
                    'summary' => $client['summary'] ?? null,
                    'brief' => $client['summary'] ?? null,
                    'status' => 'completed',
                    'starts_on' => min($clientYears).'-01-01',
                    'ends_on' => max($clientYears).'-12-31',
                    'published_at' => now(),
                ],
            );
        }

        foreach ($data['campaigns'] ?? [] as $index => $campaign) {
            $client = $parties[$campaign['client']] ?? null;

            if (! $client) {
                continue;
            }

            $project = $projects[$campaign['client']] ?? null;
            $service = $this->serviceFor($campaign['service']);
            $date = $this->parseDate($campaign['date'] ?? null, (int) $campaign['year']);
            $slug = Str::limit(Str::slug($campaign['title'].' '.$campaign['client'].' '.$campaign['year']), 200, '').'-'.$index;
            $cover = null;

            foreach ($campaign['media'] ?? [] as $key) {
                $media = $mediaByKey->get($key);
                if ($media && ! empty($media['poster'])) {
                    $cover = $media['poster'];
                    break;
                }
            }

            $record = Campaign::updateOrCreate(
                ['campaign_code' => 'work-'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'project_id' => $project?->id,
                    'client_party_id' => $client->id,
                    'advertiser_party_id' => $client->id,
                    'agency_party_id' => $raMedia->id,
                    'service_id' => $service->id,
                    'name' => $campaign['title'],
                    'slug' => $slug,
                    'summary' => $campaign['description'] ?? null,
                    'channel_label' => $campaign['channel'] ?? null,
                    'platform_label' => $campaign['platform'] ?? null,
                    'is_featured' => (bool) ($campaign['featured'] ?? false),
                    'status' => 'completed',
                    'starts_on' => $date->toDateString(),
                    'ends_on' => $date->copy()->endOfMonth()->toDateString(),
                    'published_at' => now(),
                ],
            );

            $this->replaceMedia($record, $cover, 'cover');

            if ($project) {
                ProjectService::updateOrCreate(
                    ['project_id' => $project->id, 'service_id' => $service->id],
                    ['notes' => null],
                );
            }

            $caseStudy = CaseStudy::updateOrCreate(
                ['slug' => $slug.'-case-study'],
                [
                    'title' => $campaign['title'],
                    'summary' => $campaign['description'] ?? null,
                    'challenge' => null,
                    'approach' => $campaign['description'] ?? null,
                    'outcome' => null,
                    'status' => 'published',
                    'visibility' => 'public',
                    'published_at' => now(),
                ],
            );

            CaseStudyCampaign::updateOrCreate(['case_study_id' => $caseStudy->id, 'campaign_id' => $record->id]);

            if ($project) {
                CaseStudyProject::updateOrCreate(['case_study_id' => $caseStudy->id, 'project_id' => $project->id]);
            }

            $caseStudy->clearMediaCollection('portfolio');

            foreach (array_values($campaign['media'] ?? []) as $sort => $key) {
                $media = $mediaByKey->get($key);

                if (! $media) {
                    continue;
                }

                $this->mediaFile(
                    $caseStudy,
                    $media['poster'] ?? null,
                    'portfolio',
                    'public',
                    [
                        'caption' => $media['title'] ?? null,
                        'sort_order' => $sort,
                        'usage_consent' => true,
                    ],
                );
            }
        }
    }

    private function serviceFor(string $name): Service
    {
        return Service::updateOrCreate(
            ['service_code' => Str::slug($name)],
            [
                'name' => $name,
                'slug' => Str::slug($name),
                'category' => 'Client work',
                'summary' => $name.' delivered for RA Media clients.',
                'description' => null,
                'is_featured' => false,
                'status' => 'published',
                'published_at' => now(),
            ],
        );
    }

    private function parseDate(?string $value, int $year): Carbon
    {
        try {
            return Carbon::parse($value);
        } catch (\Throwable) {
            return Carbon::create($year, 1, 1);
        }
    }
}
