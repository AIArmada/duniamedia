<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class MediaListing extends Model {
 protected $table='media_listings';
 protected $fillable=["title", "slug", "type", "channel", "location", "audience", "summary", "description", "image", "specifications", "price_from", "pricing_mode", "availability_status", "verified_at", "is_featured", "is_published", "supplier_name"];
 protected function casts(): array { return ['specifications'=>'array','verified_at'=>'datetime','price_from'=>'decimal:2','is_featured'=>'boolean','is_published'=>'boolean']; }

}
