<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaListing extends Model
{
    protected $table = 'media_listings';

    protected $fillable = ['title', 'slug', 'type', 'channel', 'location', 'audience', 'summary', 'description', 'image', 'specifications', 'price_from', 'pricing_mode', 'availability_status', 'verified_at', 'is_featured', 'is_published', 'supplier_name'];

    protected function casts(): array
    {
        return ['specifications' => 'array', 'verified_at' => 'datetime', 'price_from' => 'decimal:2', 'is_featured' => 'boolean', 'is_published' => 'boolean'];
    }

    public function displayImage(): string
    {
        if ($this->image && ! str_contains($this->image, 'assets/img/listing-')) {
            return $this->image;
        }

        return match ($this->type) {
            'TV & Broadcast' => 'assets/img/v2/channel-tv.webp',
            'Radio', 'Podcast' => 'assets/img/v2/channel-radio.webp',
            'Outdoor' => 'assets/img/v2/channel-outdoor.webp',
            'Indoor' => 'assets/img/v2/channel-spaces.webp',
            'Digital' => 'assets/img/v2/channel-digital.webp',
            'Creators' => 'assets/img/v2/channel-creators.webp',
            'Print' => 'assets/img/v2/channel-print.webp',
            default => 'assets/img/v2/channel-spaces.webp',
        };
    }
}
