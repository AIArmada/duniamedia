<?php

namespace App\Http\Controllers;

use App\Support\MediaDirectory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MediaDirectoryController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->query('q', ''));

        $owners = collect(MediaDirectory::owners())
            ->when($query !== '', fn ($owners) => $owners->filter(function (array $owner) use ($query): bool {
                $haystack = mb_strtolower(implode(' ', [
                    $owner['name'],
                    $owner['description'] ?? '',
                    implode(' ', array_column($owner['outlets'], 'name')),
                ]));

                return str_contains($haystack, mb_strtolower($query));
            }))
            ->values();

        return view('pages.media-owners', [
            'owners' => $owners,
            'query' => $query,
            'pageTitle' => 'Media Owners | Dunia Media',
            'metaDescription' => 'Browse the media owners and channel groups represented by Dunia Media, from national broadcasters to specialist publishers and out-of-home networks.',
        ]);
    }

    public function owner(string $owner)
    {
        $ownerData = MediaDirectory::owner($owner);
        abort_unless($ownerData, 404);

        return view('pages.media-owner', [
            'owner' => $ownerData,
            'rateCards' => MediaDirectory::rateCards($owner),
            'pageTitle' => $ownerData['name'].' — Media Owner | Dunia Media',
        ]);
    }

    public function outlet(string $owner, string $outlet)
    {
        $ownerData = MediaDirectory::owner($owner);
        abort_unless($ownerData, 404);

        $outletData = MediaDirectory::outlet($owner, $outlet);
        abort_unless($outletData, 404);

        $siblings = collect($ownerData['outlets'])
            ->where('slug', '!=', $outlet)
            ->take(8)
            ->values();

        return view('pages.media-outlet', [
            'owner' => $ownerData,
            'outlet' => $outletData,
            'description' => MediaDirectory::outletDescription($outletData),
            'rateCards' => MediaDirectory::rateCards($owner, $outlet),
            'siblings' => $siblings,
            'pageTitle' => $outletData['name'].' — '.$ownerData['name'].' | Dunia Media',
        ]);
    }
}
