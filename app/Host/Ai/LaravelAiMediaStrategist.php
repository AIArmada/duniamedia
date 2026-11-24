<?php

declare(strict_types=1);

namespace App\Host\Ai;

use App\Host\Ai\Agents\MediaFollowUpAgent;
use App\Host\Ai\Agents\MediaStrategyAgent;
use App\Shared\Contracts\AdvisesMediaStrategy;
use App\Shared\Contracts\AdvisorFollowUpRequest;
use App\Shared\Contracts\AdvisorFollowUpResult;
use App\Shared\Contracts\StrategySynthesisRequest;
use App\Shared\Contracts\StrategySynthesisResult;
use Illuminate\Support\Facades\Log;
use Throwable;

final class LaravelAiMediaStrategist implements AdvisesMediaStrategy
{
    public function isAvailable(): bool
    {
        if (MediaStrategyAgent::isFaked() || MediaFollowUpAgent::isFaked()) {
            return true;
        }

        return $this->isConfiguredForLiveCalls();
    }

    public function synthesize(StrategySynthesisRequest $request): ?StrategySynthesisResult
    {
        if (! MediaStrategyAgent::isFaked() && ! $this->isConfiguredForLiveCalls()) {
            return null;
        }

        try {
            $response = (new MediaStrategyAgent($request))->prompt(
                'Synthesise the media strategy for this brief.',
                provider: $this->provider(),
                model: $this->model(),
                timeout: $this->timeout(),
            );

            return $this->mapSynthesis($response->toArray(), $request);
        } catch (Throwable $exception) {
            Log::warning('Media advisor AI synthesis failed.', ['exception' => $exception->getMessage()]);

            return null;
        }
    }

    public function answerFollowUp(AdvisorFollowUpRequest $request): ?AdvisorFollowUpResult
    {
        if (! MediaFollowUpAgent::isFaked() && ! $this->isConfiguredForLiveCalls()) {
            return null;
        }

        try {
            $response = (new MediaFollowUpAgent($request))->prompt(
                $request->question,
                provider: $this->provider(),
                model: $this->model(),
                timeout: $this->timeout(),
            );

            $payload = $response->toArray();
            $reply = trim((string) ($payload['reply'] ?? ''));

            if ($reply === '') {
                return null;
            }

            return new AdvisorFollowUpResult(
                reply: $reply,
                suggestions: $this->stringList($payload['suggestions'] ?? []),
            );
        } catch (Throwable $exception) {
            Log::warning('Media advisor AI follow-up failed.', ['exception' => $exception->getMessage()]);

            return null;
        }
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function mapSynthesis(array $payload, StrategySynthesisRequest $request): ?StrategySynthesisResult
    {
        $headline = trim((string) ($payload['headline'] ?? ''));
        $diagnosis = trim((string) ($payload['diagnosis'] ?? ''));

        $allowed = [];

        foreach ($request->candidates as $candidate) {
            $allowed[$candidate['product_id']] = true;
        }

        $scenarios = [];

        foreach (($payload['scenarios'] ?? []) as $scenario) {
            if (! is_array($scenario)) {
                continue;
            }

            $key = (string) ($scenario['key'] ?? '');

            if ($key === '') {
                continue;
            }

            $productIds = [];

            foreach (($scenario['product_ids'] ?? []) as $productId) {
                if (is_string($productId) && isset($allowed[$productId])) {
                    $productIds[] = $productId;
                }
            }

            if ($productIds === []) {
                continue;
            }

            $scenarios[$key] = [
                'name' => trim((string) ($scenario['name'] ?? $key)),
                'headline' => trim((string) ($scenario['headline'] ?? '')),
                'messaging' => trim((string) ($scenario['messaging'] ?? '')),
                'creative_angle' => trim((string) ($scenario['creative_angle'] ?? '')),
                'diagnosis' => trim((string) ($scenario['diagnosis'] ?? '')),
                'product_ids' => array_values(array_unique($productIds)),
            ];
        }

        if ($headline === '' || $scenarios === []) {
            return null;
        }

        return new StrategySynthesisResult(
            headline: $headline,
            diagnosis: $diagnosis,
            scenarios: $scenarios,
            questions: $this->stringList($payload['questions'] ?? []),
        );
    }

    /**
     * @return list<string>
     */
    private function stringList(mixed $values): array
    {
        if (! is_array($values)) {
            return [];
        }

        return array_values(array_filter($values, static fn (mixed $value): bool => is_string($value) && trim($value) !== ''));
    }

    private function isConfiguredForLiveCalls(): bool
    {
        if (! (bool) config('duniamedia.advisor.ai_enabled', false)) {
            return false;
        }

        return $this->hasConfiguredProvider();
    }

    private function hasConfiguredProvider(): bool
    {
        $provider = $this->provider() ?? config('ai.default');

        if (! is_string($provider) || $provider === '') {
            return false;
        }

        $config = config('ai.providers.'.$provider);

        if (! is_array($config)) {
            return false;
        }

        $driver = (string) ($config['driver'] ?? $provider);

        if ($driver === 'ollama' || $driver === 'openai-compatible') {
            $url = $config['url'] ?? null;

            return is_string($url) && trim($url) !== '';
        }

        if ($driver === 'bedrock') {
            foreach (['key', 'access_key_id'] as $field) {
                $value = $config[$field] ?? null;

                if (is_string($value) && trim($value) !== '') {
                    return true;
                }
            }

            return false;
        }

        $key = $config['key'] ?? null;

        return is_string($key) && trim($key) !== '';
    }

    private function provider(): ?string
    {
        $provider = config('duniamedia.advisor.ai_provider');

        return is_string($provider) && $provider !== '' ? $provider : null;
    }

    private function model(): ?string
    {
        $model = config('duniamedia.advisor.ai_model');

        return is_string($model) && $model !== '' ? $model : null;
    }

    private function timeout(): int
    {
        return (int) config('duniamedia.advisor.ai_timeout', 45);
    }
}
