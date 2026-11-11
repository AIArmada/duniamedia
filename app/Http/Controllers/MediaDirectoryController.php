<?php

namespace App\Http\Controllers;

use App\Support\MediaDirectory;
use Illuminate\Routing\Controller;

class MediaDirectoryController extends Controller
{
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
