<?php

declare(strict_types=1);

namespace App\Features\Sales;

use AIArmada\Orders\Events\OrderCanceled;
use App\Features\Sales\Actions\CreateCampaignPlanFromMix;
use App\Features\Sales\Actions\RecordEnquiry;
use App\Features\Sales\Api\CreatesCampaignPlans;
use App\Features\Sales\Api\RecordsEnquiries;
use App\Features\Sales\EntryPoints\Console\GenerateProposalLink;
use App\Features\Sales\Listeners\ReleaseOrderCapacity;
use App\Platform\FeatureModule;
use Illuminate\Support\Facades\Event;

final class FeatureServiceProvider extends FeatureModule
{
    public function register(): void
    {
        $this->app->bind(RecordsEnquiries::class, RecordEnquiry::class);
        $this->app->bind(CreatesCampaignPlans::class, CreateCampaignPlanFromMix::class);
    }

    public function boot(): void
    {
        parent::boot();

        Event::listen(OrderCanceled::class, ReleaseOrderCapacity::class);

        if ($this->app->runningInConsole()) {
            $this->commands([GenerateProposalLink::class]);
        }
    }
}
