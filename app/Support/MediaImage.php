<?php
namespace App\Support;
use Illuminate\Support\Facades\Storage;
class MediaImage {
 public static function url(?string $path, string $fallback='assets/img/outdoor.webp'): string {
  if (! $path) return asset($fallback);
  if (str_starts_with($path,'assets/')) return asset($path);
  return Storage::disk('public')->url($path);
 }
}
