<?php

declare(strict_types=1);

namespace App\Features\Advisor\Models;

use App\Shared\Models\BaseModel;
use Carbon\CarbonImmutable;
use Database\Factories\AdvisorSessionFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $share_token
 * @property string $session_identifier
 * @property string $status
 * @property string $generation_mode
 * @property string $objective
 * @property string $budget_amount
 * @property string $budget_flexibility
 * @property string $currency_code
 * @property array<int, mixed>|null $regions
 * @property array<int, mixed>|null $languages
 * @property string|null $industry
 * @property string|null $audience
 * @property string $timeline
 * @property string|null $launch_window
 * @property array<int, mixed>|null $preferred_mediums
 * @property string $creative_readiness
 * @property string|null $brand_context
 * @property array<string, mixed>|null $brief_summary
 * @property array<string, mixed>|null $recommendations
 * @property string|null $selected_scenario_key
 * @property array<int, mixed>|null $customized_product_ids
 * @property array<int, mixed>|null $customized_services
 * @property string|null $saved_plan_instance
 * @property string|null $enquiry_id
 * @property string|null $requester_name
 * @property string|null $requester_email
 * @property string|null $requester_company
 * @property string|null $requester_phone
 * @property CarbonImmutable|null $generated_at
 * @property CarbonImmutable|null $submitted_at
 */
class AdvisorSession extends BaseModel
{
    protected $table = 'advisor_sessions';

    protected function casts(): array
    {
        return [
            'budget_amount' => 'decimal:2',
            'regions' => 'array',
            'languages' => 'array',
            'preferred_mediums' => 'array',
            'brief_summary' => 'array',
            'recommendations' => 'array',
            'customized_product_ids' => 'array',
            'customized_services' => 'array',
            'generated_at' => 'datetime',
            'submitted_at' => 'datetime',
        ];
    }

    /**
     * @return HasMany<AdvisorMessage, $this>
     */
    public function messages(): HasMany
    {
        return $this->hasMany(AdvisorMessage::class, 'advisor_session_id')->orderBy('created_at');
    }

    protected static function newFactory(): AdvisorSessionFactory
    {
        return AdvisorSessionFactory::new();
    }
}
