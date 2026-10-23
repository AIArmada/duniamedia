<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Quote extends Model {
 protected $table='quotes';
 protected $fillable=["reference", "lead_id", "status", "price_visibility", "subtotal_estimate", "notes", "expires_at"];
 protected function casts(): array { return ['subtotal_estimate'=>'decimal:2','expires_at'=>'datetime']; }
  public function lead(){ return $this->belongsTo(Lead::class); }
 public function items(){ return $this->hasMany(QuoteItem::class); }
}
