<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class WorkMediaController extends Controller
{
    /**
     * Serve broadcast media from local disk in development, or redirect to a
     * short-lived presigned S3 URL when WORK_MEDIA_DISK=s3.
     */
    public function show(string $key)
    {
        $files = [
            'kpj-2025-12-18' => [
                'local' => 'data/KPJ Kajang/NLKO/NLKO KPJ KAJANG SPECIALIST HOSPITAL TX 18122025.mp4',
                'remote' => 'kpj-kajang-2025-12-18.mp4',
                'mime' => 'video/mp4',
            ],
            'kpj-2025-11-26' => [
                'local' => 'data/KPJ Kajang/NLKO/NLKO KPJ HEALTHCARE KAJANG TX 26112025.mp4',
                'remote' => 'kpj-healthcare-2025-11-26.mp4',
                'mime' => 'video/mp4',
            ],
            'yayasan-maghfirah-30s' => [
                'local' => 'data/Yayasan Maghfirah/iklan 30 sec LPF_with ID Board.mp4',
                'remote' => 'yayasan-maghfirah-ramadan-30s.mp4',
                'mime' => 'video/mp4',
            ],
            'jwp-passport50wp-vo' => [
                'local' => 'data/Jabatan Wilayah Persekutuan/Passport 50WP/TVC_50WP_VO.wav',
                'remote' => 'jwp-pasport50wp-vo.wav',
                'mime' => 'audio/wav',
            ],
        ];

        abort_unless(isset($files[$key]), 404);

        $file = $files[$key];

        if (config('duniamedia.work_media_disk') === 's3') {
            $disk = Storage::disk('s3');
            $remote = trim((string) config('duniamedia.work_media_prefix', 'client-work'), '/')
                .'/'.$file['remote'];

            abort_unless($disk->exists($remote), 404);

            $url = $disk->temporaryUrl(
                $remote,
                now()->addMinutes((int) config('duniamedia.work_media_url_ttl', 120)),
                [
                    'ResponseContentType' => $file['mime'],
                    'ResponseContentDisposition' => 'inline',
                ],
            );

            return redirect()->away($url);
        }

        $path = base_path($file['local']);
        abort_unless(is_file($path), 404);

        return response()->file($path, [
            'Content-Type' => $file['mime'],
            'Content-Disposition' => 'inline',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
