<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Article extends Model {
 protected $table='articles';
 protected $fillable=["title", "slug", "category", "excerpt", "body", "image", "published_at", "is_published"];
 protected function casts(): array { return ['published_at'=>'datetime','is_published'=>'boolean']; }

}
