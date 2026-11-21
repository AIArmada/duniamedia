<?php

declare(strict_types=1);

namespace App\Features\Sales\EntryPoints\Filament;

use App\Features\Clients\Api\ListsCampaigns;
use App\Features\Identity\Api\ListsParties;
use App\Features\Sales\EntryPoints\Filament\EnquiryResource\Pages;
use App\Features\Sales\Models\Enquiry;
use App\Shared\Filament\HasResourcePermissions;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EnquiryResource extends Resource
{
    use HasResourcePermissions;

    protected static string $permission = 'enquiries.manage';

    protected static ?string $model = Enquiry::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-inbox';

    protected static string|\UnitEnum|null $navigationGroup = 'Sales Pipeline';

    protected static ?int $navigationSort = 7;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('requester_party_id')->options(fn (): array => app(ListsParties::class)->options())->searchable()->required(),
            Select::make('campaign_id')->label('Campaign')->options(fn (): array => app(ListsCampaigns::class)->options())->searchable(),
            TextInput::make('source')->required()->maxLength(255),
            TextInput::make('subject')->required()->maxLength(255),
            Textarea::make('brief')->rows(4)->columnSpanFull(),
            TextInput::make('budget_amount')->numeric()->prefix('RM')->minValue(0),
            TextInput::make('currency_code')->maxLength(3)->default('MYR'),
            Select::make('status')->options(self::statuses())->required(),
            DateTimePicker::make('consent_at'),
            DateTimePicker::make('qualified_at'),
            DateTimePicker::make('proposal_at'),
            DateTimePicker::make('won_at'),
            DateTimePicker::make('lost_at'),
            DateTimePicker::make('closed_at'),
            KeyValue::make('metadata')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('subject')->sortable()->searchable(),
            TextColumn::make('requester_name')->label('Requester')->state(fn (Enquiry $record): string => $record->contactName()),
            TextColumn::make('source')->sortable()->searchable(),
            TextColumn::make('status')->sortable()->searchable(),
            TextColumn::make('created_at')->sortable(),
        ])->recordActions([EditAction::make()])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecords::route('/'),
            'create' => Pages\CreateRecord::route('/create'),
            'edit' => Pages\EditRecord::route('/{record}'),
        ];
    }

    public static function statuses(): array
    {
        return [
            'new' => 'new',
            'contacted' => 'contacted',
            'qualified' => 'qualified',
            'proposal' => 'proposal',
            'won' => 'won',
            'lost' => 'lost',
            'closed' => 'closed',
        ];
    }
}
