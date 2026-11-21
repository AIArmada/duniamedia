<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Features\Clients\Models\Service;
use App\Features\Clients\Persistence\WriteCampaigns;
use App\Features\Clients\Persistence\WriteClientReferences;
use App\Features\Clients\Persistence\WriteProjects;
use App\Features\Clients\Persistence\WriteProjectServices;
use App\Features\Clients\Persistence\WriteServices;
use App\Features\Identity\Persistence\WriteParties;
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

        $parties = app(WriteParties::class);
        $references = app(WriteClientReferences::class);
        $projects = app(WriteProjects::class);
        $campaigns = app(WriteCampaigns::class);
        $projectServices = app(WriteProjectServices::class);
        $services = app(WriteServices::class);

        $raMedia = $parties->findBySlug('ra-media-relations')
            ?? throw new \RuntimeException('The RA Media seller party must be seeded before client work.');

        $mediaByKey = collect($data['media'] ?? [])->keyBy('key');
        $clientParties = [];
        $clientProjects = [];
        $years = [];

        foreach ($data['clients'] ?? [] as $client) {
            $party = $parties->party(
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

            $parties->organization(
                ['party_id' => $party->id],
                [
                    'industry' => $client['industry'] ?? null,
                    'organization_type' => $client['sector'] ?? 'business',
                ],
            );

            $parties->role(
                ['party_id' => $party->id, 'role_code' => 'client'],
                ['valid_from' => now()->startOfYear()],
            );

            $references->upsert(
                ['client_party_id' => $party->id, 'reference_type' => 'client_logo'],
                [
                    'permission_granted' => true,
                    'public_label' => $client['short_name'] ?? $client['name'],
                ],
            );

            $clientParties[$client['slug']] = $party;
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

            $clientProjects[$client['slug']] = $projects->upsert(
                ['project_code' => 'work-'.$client['slug']],
                [
                    'client_party_id' => $clientParties[$client['slug']]->id,
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
            $client = $clientParties[$campaign['client']] ?? null;

            if (! $client) {
                continue;
            }

            $project = $clientProjects[$campaign['client']] ?? null;
            $service = $this->serviceFor($services, $campaign['service']);
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

            $record = $campaigns->upsert(
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
                    'medium' => $campaign['channel'] ?? null,
                    'channel_label' => $campaign['platform'] ?? null,
                    'is_featured' => (bool) ($campaign['featured'] ?? false),
                    'status' => 'completed',
                    'starts_on' => $date->toDateString(),
                    'ends_on' => $date->copy()->endOfMonth()->toDateString(),
                    'published_at' => now(),
                ],
            );

            $this->replaceMedia($record, $cover, 'cover');

            if ($project) {
                $projectServices->upsert(
                    ['project_id' => $project->id, 'service_id' => $service->id],
                    ['notes' => null],
                );
            }

            $record->clearMediaCollection('media');

            foreach (array_values($campaign['media'] ?? []) as $sort => $key) {
                $media = $mediaByKey->get($key);

                if (! $media) {
                    continue;
                }

                $this->mediaFile(
                    $record,
                    $media['poster'] ?? null,
                    'media',
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

    private function serviceFor(WriteServices $services, string $name): Service
    {
        return $services->upsert(
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
