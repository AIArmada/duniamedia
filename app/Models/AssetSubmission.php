<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AssetSubmission extends Model {
 protected $table='asset_submissions';
 protected $fillable=["name", "email", "phone", "company", "asset_type", "location", "image", "description", "status", "consent_at"];
 protected function casts(): array { return ['consent_at'=>'datetime']; }

}
