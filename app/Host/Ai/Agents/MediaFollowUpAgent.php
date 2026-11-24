<?php

declare(strict_types=1);

namespace App\Host\Ai\Agents;

use App\Shared\Contracts\AdvisorFollowUpRequest;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;

final class MediaFollowUpAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function __construct(private readonly AdvisorFollowUpRequest $request) {}

    public function instructions(): string
    {
        $request = $this->request;

        $lines = [
            'You are a senior Malaysian media planner at Dunia Media.',
            'Answer the buyer\'s refinement question using only the supplied catalogue candidates and active scenarios.',
            'Be specific, concise and commercially honest. Reference candidates by name, never invent inventory or confirmed rates.',
            '',
            'Active brief',
            json_encode($request->brief, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: '{}',
            '',
            'Active scenarios',
        ];

        foreach ($request->scenarios as $scenario) {
            $lines[] = sprintf(
                '%s | %s | estimated %s',
                $scenario['key'],
                $scenario['name'],
                $scenario['estimated_subtotal'] === null ? 'on request' : number_format($scenario['estimated_subtotal']),
            );
        }

        $lines[] = '';
        $lines[] = 'Catalogue candidates';

        foreach ($request->candidates as $candidate) {
            $lines[] = sprintf(
                '%s | %s | %s | %s',
                $candidate['product_id'],
                $candidate['name'],
                $candidate['medium'] ?? 'unspecified',
                $candidate['price_from'] === null ? 'on request' : number_format($candidate['price_from']),
            );
        }

        return implode("\n", $lines);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'reply' => $schema->string()->required(),
            'suggestions' => $schema->array()->items($schema->string())->required(),
        ];
    }
}
