<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CreatorApplication extends Model {
 protected $table='creator_applications';
 protected $fillable=["name", "email", "phone", "handle", "platform", "niche", "followers", "message", "status", "consent_at"];
 protected function casts(): array { return ['consent_at'=>'datetime']; }

}
