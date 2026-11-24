<?php

declare(strict_types=1);

namespace App\Host\Ai\Agents;

use App\Shared\Contracts\StrategySynthesisRequest;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;

final class MediaStrategyAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function __construct(private readonly StrategySynthesisRequest $request) {}

    public function instructions(): string
    {
        $brief = $this->request;

        $lines = [
            'You are a senior Malaysian media planner at Dunia Media (RA Media Relations Sdn Bhd).',
            'Produce grounded, commercially realistic media strategy for the brief below using ONLY the supplied catalogue candidates.',
            'Never invent catalogue opportunities: reference candidates strictly by their product_id values.',
            'Return three scenarios keyed lean, balanced and impact, honouring the budget bands: lean is efficient and focused, balanced is the connected multi-channel recommendation, impact is a high-reach stretch.',
            'Write in clear Malaysian English. Keep the tone advisory, never a confirmed quotation.',
            '',
            'Brief',
            '- Objective: '.$brief->objective,
            '- Budget: '.number_format($brief->budgetAmount).' '.$brief->currencyCode,
            '- Regions: '.implode(', ', $brief->regions),
            '- Languages: '.implode(', ', $brief->languages),
            '- Industry: '.($brief->industry ?? 'unspecified'),
            '- Audience: '.($brief->audience ?? 'unspecified'),
            '- Timeline: '.$brief->timeline,
            '- Launch window: '.($brief->launchWindow ?? 'unspecified'),
            '- Preferred mediums: '.($brief->preferredMediums === [] ? 'planner discretion' : implode(', ', $brief->preferredMediums)),
            '- Creative readiness: '.$brief->creativeReadiness,
            '- Context: '.($brief->brandContext ?? 'none supplied'),
            '',
            'Catalogue candidates (product_id | name | medium | location | channel | audience | indicative price)',
        ];

        foreach ($brief->candidates as $candidate) {
            $lines[] = sprintf(
                '%s | %s | %s | %s | %s | %s | %s',
                $candidate['product_id'],
                $candidate['name'],
                $candidate['medium'] ?? 'unspecified',
                $candidate['location'] ?? 'nationwide',
                $candidate['channel'] ?? 'unspecified',
                $candidate['audience'] ?? 'unspecified',
                $candidate['price_from'] === null ? 'on request' : number_format($candidate['price_from']).' '.$brief->currencyCode,
            );
        }

        return implode("\n", $lines);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'headline' => $schema->string()->required(),
            'diagnosis' => $schema->string()->required(),
            'scenarios' => $schema->array()->items(
                $schema->object([
                    'key' => $schema->string()->required(),
                    'name' => $schema->string()->required(),
                    'headline' => $schema->string()->required(),
                    'messaging' => $schema->string()->required(),
                    'creative_angle' => $schema->string()->required(),
                    'diagnosis' => $schema->string()->required(),
                    'product_ids' => $schema->array()->items($schema->string())->required(),
                ])
            )->required(),
            'questions' => $schema->array()->items($schema->string())->required(),
        ];
    }
}
