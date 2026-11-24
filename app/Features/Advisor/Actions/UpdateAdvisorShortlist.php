<?php

declare(strict_types=1);

namespace App\Features\Advisor\Actions;

use App\Features\Advisor\Models\AdvisorSession;
use App\Features\Advisor\Persistence\WriteAdvisorSessions;
use App\Features\Catalog\Api\PresentsMedia;

final readonly class UpdateAdvisorShortlist
{
    public function __construct(
        private PresentsMedia $media,
        private WriteAdvisorSessions $sessions,
    ) {}

    /**
     * @param  list<string>  $productIds
     * @param  list<string>  $services
     */
    public function handle(AdvisorSession $session, ?string $scenarioKey, array $productIds, array $services): AdvisorSession
    {
        $valid = [];

        foreach ($productIds as $productId) {
            if ($this->media->contains($productId) && ! in_array($productId, $valid, true)) {
                $valid[] = $productId;
            }
        }

        $serviceSlugs = [];

        foreach ($services as $service) {
            $slug = trim((string) $service);

            if ($slug !== '' && ! in_array($slug, $serviceSlugs, true)) {
                $serviceSlugs[] = $slug;
            }
        }

        return $this->sessions->update($session, [
            'selected_scenario_key' => $scenarioKey,
            'customized_product_ids' => $valid,
            'customized_services' => $serviceSlugs,
        ]);
    }
}
