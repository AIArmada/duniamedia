<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Training extends Model {
 protected $table='trainings';
 protected $fillable=["title", "slug", "category", "summary", "description", "format", "duration", "image", "is_published"];
 protected function casts(): array { return ['is_published'=>'boolean']; }

}
