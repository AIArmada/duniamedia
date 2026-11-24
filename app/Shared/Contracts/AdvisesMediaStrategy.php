<?php

declare(strict_types=1);

namespace App\Shared\Contracts;

interface AdvisesMediaStrategy
{
    public function isAvailable(): bool;

    public function synthesize(StrategySynthesisRequest $request): ?StrategySynthesisResult;

    public function answerFollowUp(AdvisorFollowUpRequest $request): ?AdvisorFollowUpResult;
}
