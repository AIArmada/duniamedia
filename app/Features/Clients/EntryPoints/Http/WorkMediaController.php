<?php

declare(strict_types=1);

namespace App\Features\Clients\EntryPoints\Http;

use App\Features\Clients\Support\ClientWork;
use App\Shared\Contracts\StoresWorkMedia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class WorkMediaController extends Controller
{
    public function show(Request $request, string $key, StoresWorkMedia $workMedia): BinaryFileResponse|StreamedResponse|RedirectResponse
    {
        $media = collect(ClientWork::media())->firstWhere('key', $key);
        abort_unless($media && ! empty($media['file']), 404);

        $fileName = (string) $media['file'];
        $path = $workMedia->localPath($fileName);

        if ($path === null) {
            $url = $workMedia->temporaryUrl($fileName, $this->mime($fileName));

            abort_if($url === null, 404);

            return redirect()->away($url);
        }

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
