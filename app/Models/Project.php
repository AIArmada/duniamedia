<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Project extends Model {
 protected $table='projects';
 protected $fillable=["title", "slug", "category", "client_name", "summary", "description", "image", "is_concept", "is_published", "sort_order"];
 protected function casts(): array { return ['is_concept'=>'boolean','is_published'=>'boolean']; }

}
