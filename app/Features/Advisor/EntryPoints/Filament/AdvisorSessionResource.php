<?php

declare(strict_types=1);

namespace App\Features\Advisor\EntryPoints\Filament;

use App\Features\Advisor\EntryPoints\Filament\AdvisorSessionResource\Pages;
use App\Features\Advisor\Models\AdvisorSession;
use App\Shared\Filament\HasResourcePermissions;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AdvisorSessionResource extends Resource
{
    use HasResourcePermissions;

    protected static string $permission = 'enquiries.manage';

    protected static ?string $model = AdvisorSession::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';

    protected static string|\UnitEnum|null $navigationGroup = 'Sales Pipeline';

    protected static ?int $navigationSort = 8;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('share_token')->label('Share token')->disabled()->dehydrated(false),
            TextInput::make('objective')->disabled()->dehydrated(false),
            TextInput::make('budget_amount')->numeric()->prefix('RM')->minValue(0),
            Select::make('selected_scenario_key')->label('Selected scenario')->options([
                'lean' => 'Focused & Agile',
                'balanced' => 'Connected Multi-Channel',
                'impact' => 'High-Impact Reach',
            ]),
            TextInput::make('generation_mode')->label('Generation mode')->disabled()->dehydrated(false),
            Select::make('status')->options([
                'draft' => 'draft',
                'generated' => 'generated',
                'planned' => 'planned',
                'submitted' => 'submitted',
            ])->required(),
            TextInput::make('requester_name')->label('Requester'),
            TextInput::make('requester_email')->label('Email')->email(),
            TextInput::make('requester_company')->label('Company'),
            TextInput::make('requester_phone')->label('Phone'),
            TextInput::make('saved_plan_instance')->label('Campaign plan')->disabled()->dehydrated(false),
            TextInput::make('enquiry_id')->label('Enquiry')->disabled()->dehydrated(false),
            Textarea::make('brand_context')->label('Campaign context')->rows(3)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('share_token')->label('Reference')->searchable(),
            TextColumn::make('objective')->sortable()->searchable(),
            TextColumn::make('budget_amount')->money('MYR')->sortable(),
            TextColumn::make('status')->badge()->sortable()->searchable(),
            TextColumn::make('generation_mode')->label('Mode')->badge()->sortable(),
            TextColumn::make('selected_scenario_key')->label('Scenario'),
            TextColumn::make('messages_count')->label('Messages')->counts('messages')->sortable(),
            TextColumn::make('requester_name')->label('Requester')->state(fn (AdvisorSession $record): string => (string) ($record->requester_name ?? '—')),
            TextColumn::make('saved_plan_instance')->label('Plan')->toggleable(),
            TextColumn::make('enquiry_id')->label('Enquiry')->toggleable(),
            TextColumn::make('created_at')->sortable(),
        ])->recordActions([EditAction::make()])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecords::route('/'),
            'edit' => Pages\EditRecord::route('/{record}'),
        ];
    }
}
