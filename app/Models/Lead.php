<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Lead extends Model {
 protected $table='leads';
 protected $fillable=["name", "email", "phone", "company", "source", "objective", "budget", "location", "message", "status", "consent_at", "metadata"];
 protected function casts(): array { return ['metadata'=>'array','consent_at'=>'datetime']; }
  public function quotes(){ return $this->hasMany(Quote::class); }
}
