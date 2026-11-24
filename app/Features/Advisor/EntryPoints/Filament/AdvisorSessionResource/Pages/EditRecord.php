<?php

declare(strict_types=1);

namespace App\Features\Advisor\EntryPoints\Filament\AdvisorSessionResource\Pages;

use App\Features\Advisor\EntryPoints\Filament\AdvisorSessionResource;
use App\Features\Advisor\Models\AdvisorSession;
use App\Features\Advisor\Persistence\WriteAdvisorSessions;
use Illuminate\Database\Eloquent\Model;

class EditRecord extends \Filament\Resources\Pages\EditRecord
{
    protected static string $resource = AdvisorSessionResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $session = $record instanceof AdvisorSession
            ? $record
            : AdvisorSession::query()->findOrFail($record->getKey());

        return app(WriteAdvisorSessions::class)->update($session, $data);
    }
}
