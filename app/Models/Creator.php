<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Creator extends Model {
 protected $table='creators';
 protected $fillable=["name", "slug", "handle", "platform", "niche", "location", "bio", "image", "followers", "average_views", "engagement_rate", "rate_from", "pricing_mode", "verified_at", "status", "is_featured"];
 protected function casts(): array { return ['followers'=>'integer','average_views'=>'integer','engagement_rate'=>'decimal:2','rate_from'=>'decimal:2','verified_at'=>'datetime','is_featured'=>'boolean']; }

}
