<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Service extends Model {
 protected $table='services';
 protected $fillable=["title", "slug", "category", "summary", "description", "icon", "image", "is_featured", "is_published", "sort_order"];
 protected function casts(): array { return ['is_featured'=>'boolean','is_published'=>'boolean']; }

}
