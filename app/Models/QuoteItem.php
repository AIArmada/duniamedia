<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class QuoteItem extends Model {
 protected $table='quote_items';
 protected $fillable=["quote_id", "media_listing_id", "name", "kind", "quantity", "unit_price", "price_visibility"];
 protected function casts(): array { return ['unit_price'=>'decimal:2']; }
  public function quote(){ return $this->belongsTo(Quote::class); }
 public function mediaListing(){ return $this->belongsTo(MediaListing::class); }
}
