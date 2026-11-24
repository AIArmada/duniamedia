<?php

declare(strict_types=1);

namespace App\Features\Sales\EntryPoints\Http;

use App\Features\Catalog\Api\PresentsRateLines;
use App\Features\Sales\Support\CampaignPlans;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;

class PlanController extends Controller
{
    public function addRate(string $entry, PresentsRateLines $rateLines, CampaignPlans $plans): RedirectResponse
    {
        $line = $rateLines->find($entry);

        abort_if($line === null || ! $line->isPriced(), 404);

        $plan = $plans->draft();
        $plans->addRateLine($plan, $line);
        $summary = $plans->summary($plan);

        return back()->with('plan_added', [
            'instance' => $plan->instance,
            'name' => $line->item,
            'count' => (int) $summary['items']->sum(fn ($item): int => $item->quantity),
            'total' => $summary['total'] / 100,
        ]);
    }
}
