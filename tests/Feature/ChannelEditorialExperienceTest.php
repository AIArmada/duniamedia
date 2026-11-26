<?php

declare(strict_types=1);

namespace Tests\Feature;

use AIArmada\Products\Models\Product;
use App\Features\Catalog\Models\MediaProperty;
use App\Features\Catalog\Support\ChannelEditorial;
use App\Features\Catalog\Support\MediaDirectory;
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
        foreach (MediaDirectory::owners() as $slug => $owner) {
            foreach ($owner['channels'] as $channel) {
                $response = $this->get(route('media.channel', [$slug, $channel['slug']]))->assertOk();

                if ($slug === 'media-prima' && $channel['slug'] === 'hot-fm') {
                    $response->assertSee('Get heard.')->assertSee('hotfm-page', false);
                    continue;
                }

                $visual = ChannelEditorial::for($channel, null);
                $this->assertFileExists(public_path($visual['image']));
                $response->assertSee('class="channel-editorial"', false)
                    ->assertSee($channel['name'])
                    ->assertSee($visual['image'])
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

        $this->get('/media/owners/astro/unknown-channel')->assertNotFound();
    }

    public function test_public_catalogue_channels_preserve_rate_lines_and_purchase_actions(): void
    {
        $this->seed(MediaCatalogueSeeder::class);
        $this->seed(RateCardContentSeeder::class);

        foreach (MediaProperty::query()->where('is_public', true)->orderBy('slug')->get() as $property) {
            $response = $this->get(route('media.channel.show', $property->slug))->assertOk();

            if ($property->slug === 'media-prima-hot-fm') {
                $response->assertSee('hotfm-page', false);
                continue;
            }

            $response->assertSee('class="channel-editorial"', false)
                ->assertSee($property->name)
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

    public function test_generated_owner_artwork_has_clear_provenance(): void
    {
        foreach (['cinema-ads' => 'cinema', 'ktm' => 'rail', 'outdoor-media' => 'outdoor', 'rtm' => 'radio'] as $slug => $image) {
            $path = 'assets/editorial/'.$image.'.webp';
            $this->assertFileExists(public_path($path));
            $this->get(route('media.owner', $slug))
                ->assertOk()->assertSee($path)->assertSee('Illustrative media environment');
        }

        $this->get(route('media.owner', 'media-prima'))
            ->assertOk()->assertSee('assets/home/tv3-newsroom.webp')
            ->assertSee('assets/home/big-tree-klcc.webp');
    }
}
