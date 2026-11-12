<?php

namespace App\Http\Controllers;

use App\Models\MediaOffering;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RateCardController extends Controller
{
    public function show(Request $request, string $slug, int $index = 0): BinaryFileResponse|StreamedResponse|RedirectResponse
    {
        $media = MediaOffering::query()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $view = data_get($media->specifications, 'rate_card_view');

        if ($index === 0 && $view && ! $request->boolean('raw')) {
            return redirect()->to(route('media.show', $media->slug).'#rate-card');
        }

        $documents = $media->getMedia('documents');
        $document = $documents[$index] ?? null;
        abort_unless($document, 404);

        $path = $document->getPath();
        abort_unless(is_file($path), 404);

        $mime = $document->mime_type ?: 'application/octet-stream';

        $headers = [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
            'Cache-Control' => 'private, max-age=3600',
            'X-Content-Type-Options' => 'nosniff',
        ];

        if ($mime === 'video/mp4' && $request->headers->has('Range')) {
            return $this->streamRange($request, $path, $headers);
        }

        return response()->file($path, $headers);
    }

    /**
     * Minimal single-range support so browsers can seek in hosted videos.
     */
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
