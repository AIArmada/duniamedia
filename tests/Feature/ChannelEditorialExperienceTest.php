<?php

declare(strict_types=1);

namespace Tests\Feature;

use AIArmada\Products\Models\Product;
use App\Features\Catalog\Models\MediaProperty;
use App\Features\Catalog\Support\ChannelEditorial;
use App\Features\Catalog\Support\MediaDirectory;
use App\Features\Catalog\Support\MediaBrandTheme;
use App\Features\Catalog\Support\RateCardPresenter;
use Database\Seeders\MediaCatalogueSeeder;
use Database\Seeders\RateCardContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesMediaOffers;
use Tests\TestCase;

class ChannelEditorialExperienceTest extends TestCase
{
    use CreatesMediaOffers;
    use RefreshDatabase;

    public function test_every_directory_channel_has_a_polished_editorial_page_even_without_rates(): void
    {
        $images = [];
        $taglines = [];
        $introductions = [];
        $count = 0;

        foreach (MediaDirectory::owners() as $slug => $owner) {
            foreach ($owner['channels'] as $channel) {
                $response = $this->get(route('media.channel', [$slug, $channel['slug']]))->assertOk();
                $visual = ChannelEditorial::for($channel, null);
                $this->assertTrue($visual['curated'], $slug.':'.$channel['slug']);
                $this->assertNotEmpty($visual['intro']);
                $this->assertCount(2, $visual['ideas']);
                $this->assertFileExists(public_path($visual['image']));
                $this->assertNotContains($visual['image'], $images);
                $this->assertNotContains($visual['tagline'], $taglines);
                $this->assertNotContains($visual['intro'], $introductions);
                $images[] = $visual['image'];
                $taglines[] = $visual['tagline'];
                $introductions[] = $visual['intro'];
                $count++;

                if ($slug === 'media-prima' && $channel['slug'] === 'hot-fm') {
                    $response->assertSee('Get heard.')->assertSee('hotfm-page', false);
                    continue;
                }

                $response->assertSee('class="channel-editorial"', false)
                    ->assertSee($channel['name'])
                    ->assertSee($visual['image'])
                    ->assertSee($visual['tagline'])
                    ->assertSee('data-brand-layout=', false)
                    ->assertSee('--brand-action:', false)
                    ->assertSee($visual['intro'])
                    ->assertSee($visual['ideas'][0]['title'])
                    ->assertSee($visual['ideas'][0]['body'])
                    ->assertSee($visual['ideas'][1]['title'])
                    ->assertSee($visual['ideas'][1]['body'])
                    ->assertSee('Rates & packages')
                    ->assertSee('href="#channel-rates-title"', false)
                    ->assertSee('href="#channel-formats"', false)
                    ->assertSee(route('media.owner', $slug))
                    ->assertSee(route('advisor'))
                    ->assertSee(route('quote.builder'))
                    ->assertSee('Request current rates')
                    ->assertDontSee('directory-hero--channel', false)
                    ->assertDontSee('.pdf', false);

                if ($visual['illustrated']) {
                    $response->assertSee('Illustrative media environment');
                }
            }
        }

        $this->assertSame(129, $count);
        $this->assertCount($count, array_unique(array_map(fn (string $path): string => (string) hash_file('sha256', public_path($path)), $images)));
        $this->get('/media/owners/astro/unknown-channel')->assertNotFound();
    }

    public function test_public_catalogue_channels_preserve_rate_lines_and_purchase_actions(): void
    {
        $this->seed(MediaCatalogueSeeder::class);
        $this->seed(RateCardContentSeeder::class);

        foreach (MediaProperty::query()->where('is_public', true)->orderBy('slug')->get() as $property) {
            $response = $this->get(route('media.channel.show', $property->slug))->assertOk();
            $channel = MediaDirectory::channelData($property->slug);
            $this->assertNotNull($channel);
            $visual = ChannelEditorial::for($channel, $property->hero_path);
            $this->assertTrue($visual['curated'], $property->slug);
            $this->assertFileExists(public_path($visual['image']));

            if ($property->slug === 'media-prima-hot-fm') {
                $response->assertSee('hotfm-page', false);
                continue;
            }

            $response->assertSee('class="channel-editorial"', false)
                ->assertSee($property->name)
                ->assertSee($visual['tagline'])
                ->assertSee($visual['ideas'][0]['title'])
                ->assertSee($visual['image'])
                ->assertSee('Rates & packages')
                ->assertDontSee('.pdf', false);

            $sections = RateCardPresenter::forChannel($property->id);

            foreach ($sections as $section) {
                $response->assertSee($section['title'])
                    ->assertSee('id="channel-rate-'.$section['id'].'"', false);

                foreach ($section['entries'] as $entry) {
                    if ($section['is_media_value'] || $entry['price'] === null || $entry['price'] <= 0) {
                        $response->assertDontSee(route('plans.rate.add', $entry['id']), false);
                    } else {
                        $response->assertSee(route('plans.rate.add', $entry['id']), false);
                    }
                }
            }
        }

        $this->get('/media/channels/unknown-channel')->assertNotFound();
    }

    public function test_channels_without_associated_inventory_do_not_inherit_unrelated_group_rates(): void
    {
        $this->createMediaOffer(['slug' => 'omnia-content-terhangat-2026', 'name' => 'Television reference']);

        $this->get(route('media.channel', ['media-prima', 'tv3']))
            ->assertOk()->assertSee(route('offers.show', 'omnia-content-terhangat-2026'));

        $this->get(route('media.channel', ['media-prima', 'fly-fm']))
            ->assertOk()->assertSee('Request current rates')
            ->assertDontSee(route('offers.show', 'omnia-content-terhangat-2026'), false);
    }

    public function test_draft_rate_sections_are_not_exposed_on_public_channel_pages(): void
    {
        $this->seed(MediaCatalogueSeeder::class);
        $this->seed(RateCardContentSeeder::class);

        $property = MediaProperty::query()->where('slug', 'star-media-group-988-fm')->firstOrFail();
        $sections = RateCardPresenter::forChannel($property->id);
        $this->assertNotEmpty($sections);

        $product = Product::query()->where('slug', '988-fm-2026-radio-digital')->firstOrFail();
        $product->update(['status' => 'draft']);

        $this->assertSame([], RateCardPresenter::forChannel($property->id));
        $this->get(route('media.channel.show', $property->slug))
            ->assertOk()->assertSee('Request current rates')
            ->assertDontSee(route('offers.show', $product->slug), false);
    }

    public function test_published_programme_and_campaign_evidence_is_channel_specific(): void
    {
        $this->get(route('media.channel', ['media-prima', 'fly-fm']))
            ->assertOk()->assertSee('Fly FM Wonda Breakfast')->assertSee('The Ryan Show')
            ->assertSee('https://www.flyfm.audio/shows/')
            ->assertDontSee('Molek Pagi');
        $this->get(route('media.channel', ['media-prima', 'molek-fm']))
            ->assertOk()->assertSee('Molek Pagi')->assertSee('Molek Petang')
            ->assertSee('https://www.molekfm.audio/segmen/')
            ->assertDontSee('Fly FM Wonda Breakfast');
        $this->get(route('media.channel', ['media-prima', 'says']))
            ->assertOk()->assertSee('NIVEA Men Deodorant')->assertSee('1M views each');
        $this->get(route('media.channel', ['astro', 'era']))
            ->assertOk()->assertSee('3 Pagi ERA')->assertSee('Hallyu ERA')
            ->assertSee('assets/channels/individual/astro-era.webp');
        $this->get(route('media.channel', ['astro', 'astro-ria']))
            ->assertOk()->assertSee('Gempak Most Wanted Bytes 2026')
            ->assertSee('assets/channels/individual/astro-astro-ria.webp');
    }

    public function test_every_owner_has_individually_selected_channel_highlights_and_distinct_artwork(): void
    {
        $editorial = require resource_path('data/media-owner-editorial.php');
        $images = [];

        foreach (MediaDirectory::owners() as $slug => $owner) {
            $config = $editorial[$slug];
            $response = $this->get(route('media.owner', $slug))->assertOk()
                ->assertSee($config['featured_channel_title'])
                ->assertSee($config['featured_channel_intro'])
                ->assertSee('id="owner-spotlights"', false);

            foreach ($config['featured_channels'] as $name) {
                $channel = collect($owner['channels'])->firstWhere('name', $name);
                $this->assertNotNull($channel, $slug.':'.$name);
                $profile = ChannelEditorial::for($channel, null);
                $response->assertSee($profile['tagline'])
                    ->assertSee($profile['image'])
                    ->assertSee(route('media.channel', [$slug, $channel['slug']]));
            }

            foreach (['hero', 'closing_image'] as $field) {
                $path = $config[$field];
                $this->assertFileExists(public_path($path));
                $this->assertNotContains($path, $images);
                $images[] = $path;
            }
        }
    }

    public function test_generated_owner_artwork_has_clear_provenance(): void
    {
        foreach (['astro', 'rtm', 'bernama', 'outdoor-media', 'cinema-ads', 'ktm'] as $slug) {
            $path = 'assets/editorial/owners/'.$slug.'-hero.webp';
            $this->assertFileExists(public_path($path));
            $this->get(route('media.owner', $slug))
                ->assertOk()->assertSee($path)->assertSee('Illustrative media environment');
        }

        $this->get(route('media.owner', 'media-prima'))
            ->assertOk()->assertSee('assets/home/tv3-newsroom.webp')
            ->assertSee('assets/home/big-tree-klcc.webp');
    }
    public function test_brand_themes_cover_every_owner_and_campaign_pathway(): void
    {
        $layouts = [];
        $styles = [];
        $titles = [];

        foreach (MediaDirectory::owners() as $slug => $owner) {
            $theme = MediaBrandTheme::owner($owner);
            $this->assertNotContains($theme['style'], $styles);
            $this->assertNotContains($theme['title'], $titles);
            $styles[] = $theme['style'];
            $titles[] = $theme['title'];
            $layouts[] = $theme['layout'];
            $this->assertNotEmpty($theme['routes']);

            $response = $this->get(route('media.owner', $slug))->assertOk()
                ->assertSee('data-brand-layout="'.$theme['layout'].'"', false)
                ->assertSee('--brand-accent:'.$theme['accent'], false)
                ->assertSee('id="owner-campaign-pathways"', false)
                ->assertSee($theme['title'])
                ->assertSee($theme['intro']);

            foreach ($theme['routes'] as [$title, $body, $names]) {
                $response->assertSee($title)->assertSee($body);
                foreach ($names as $name) {
                    $channel = collect($owner['channels'])->firstWhere('name', $name);
                    $this->assertNotNull($channel, $slug.':'.$name);
                    $response->assertSee(route('media.channel', [$slug, $channel['slug']]));
                }
            }
        }

        $this->assertGreaterThanOrEqual(6, count(array_unique($layouts)));
    }

    public function test_channel_palettes_are_individual_and_accessible(): void
    {
        foreach (MediaDirectory::owners() as $owner) {
            foreach ($owner['channels'] as $channel) {
                $profile = ChannelEditorial::for($channel, null);
                $theme = MediaBrandTheme::channel($profile, $owner);
                $this->assertSame($profile['accent'], $theme['accent']);
                preg_match('/--brand-action-contrast:([0-9.]+)/', $theme['style'], $matches);
                $this->assertGreaterThanOrEqual(4.5, (float) $matches[1]);
            }
        }

        $bright = MediaBrandTheme::palette('#ffff00', '#ffffff', 'invalid', 'invalid');
        $this->assertSame('network', $bright['layout']);
        $this->assertSame('sans', $bright['type']);
        preg_match('/--brand-action-contrast:([0-9.]+)/', $bright['style'], $matches);
        $this->assertGreaterThanOrEqual(4.5, (float) $matches[1]);

        $unsafe = MediaBrandTheme::palette('red;display:none', null, 'network', 'sans');
        $this->assertSame('#500038', $unsafe['accent']);
        $this->assertStringNotContainsString('display:none', $unsafe['style']);
    }

}
