<?php

namespace App\Http\Controllers;

use App\Support\ClientWork;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class WorkMediaController extends Controller
{
    /**
     * Serve portfolio media from private storage, with optional S3 redirect.
     */
    public function show(Request $request, string $key): BinaryFileResponse|StreamedResponse|RedirectResponse
    {
        $media = collect(ClientWork::media())->firstWhere('key', $key);
        abort_unless($media, 404);

        $path = ! empty($media['file'])
            ? storage_path('app/private/work/'.$media['file'])
            : base_path($this->legacyLocalPath($key));

        // Local files win; fall back to S3 only when the file is not on disk.
        if (! is_file($path) && config('duniamedia.work_media_disk') === 's3') {
            $disk = Storage::disk('s3');
            $remote = trim((string) config('duniamedia.work_media_prefix', 'client-work'), '/').'/'.$this->remoteName($media);

            abort_unless($disk->exists($remote), 404);

            $url = $disk->temporaryUrl(
                $remote,
                now()->addMinutes((int) config('duniamedia.work_media_url_ttl', 120)),
                [
                    'ResponseContentType' => $this->mime($remote),
                    'ResponseContentDisposition' => 'inline',
                ],
            );

            return redirect()->away($url);
        }

        abort_unless(is_file($path), 404);

        $headers = [
            'Content-Type' => $this->mime($path),
            'Content-Disposition' => 'inline',
            'Cache-Control' => 'private, max-age=3600',
            'X-Content-Type-Options' => 'nosniff',
        ];

        if ($request->headers->has('Range')) {
            return $this->streamRange($request, $path, $headers);
        }

        return response()->file($path, $headers);
    }

    private function mime(string $path): string
    {
        return match (strtolower(pathinfo($path, PATHINFO_EXTENSION))) {
            'mp3' => 'audio/mpeg',
            'wav' => 'audio/wav',
            'm4a' => 'audio/mp4',
            default => 'video/mp4',
        };
    }

    private function remoteName(array $media): string
    {
        if (! empty($media['file'])) {
            return basename($media['file']);
        }

        return match ($media['key']) {
            'kpj-2025-12-18' => 'kpj-kajang-2025-12-18.mp4',
            'kpj-2025-11-26' => 'kpj-healthcare-2025-11-26.mp4',
            'yayasan-maghfirah-30s' => 'yayasan-maghfirah-ramadan-30s.mp4',
            'jwp-passport50wp-vo' => 'jwp-pasport50wp-vo.wav',
            default => basename((string) ($media['file'] ?? '')),
        };
    }

    private function legacyLocalPath(string $key): string
    {
        return match ($key) {
            'kpj-2025-12-18' => 'data/KPJ Kajang/NLKO/NLKO KPJ KAJANG SPECIALIST HOSPITAL TX 18122025.mp4',
            'kpj-2025-11-26' => 'data/KPJ Kajang/NLKO/NLKO KPJ HEALTHCARE KAJANG TX 26112025.mp4',
            'yayasan-maghfirah-30s' => 'data/Yayasan Maghfirah/iklan 30 sec LPF_with ID Board.mp4',
            'jwp-passport50wp-vo' => 'data/Jabatan Wilayah Persekutuan/Passport 50WP/TVC_50WP_VO.wav',
            default => 'data/'.$key,
        };
    }

    private function streamRange(Request $request, string $path, array $headers): StreamedResponse
    {
        $size = filesize($path);
        $range = (string) $request->headers->get('Range');
        $start = 0;
        $end = $size - 1;

        if (preg_match('/bytes=(\d*)-(\d*)/', $range, $m)) {
            $start = $m[1] !== '' ? (int) $m[1] : 0;
            $end = $m[2] !== '' ? (int) $m[2] : $end;
        }

        $start = max(0, min($start, $size - 1));
        $end = max($start, min($end, $size - 1));
        $length = $end - $start + 1;

        return response()->stream(function () use ($path, $start, $length): void {
            $handle = fopen($path, 'rb');
            fseek($handle, $start);
            $remaining = $length;

            while ($remaining > 0 && ! feof($handle)) {
                $chunk = fread($handle, min(8192, $remaining));
                if ($chunk === false) {
                    break;
                }
                echo $chunk;
                $remaining -= strlen($chunk);
                flush();
            }

            fclose($handle);
        }, 206, array_merge($headers, [
            'Accept-Ranges' => 'bytes',
            'Content-Range' => "bytes {$start}-{$end}/{$size}",
            'Content-Length' => (string) $length,
        ]));
    }
}
