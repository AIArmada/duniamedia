<?php

declare(strict_types=1);

namespace App\Features\Sales\EntryPoints\Filament;

use App\Features\Clients\Api\ListsCampaigns;
use App\Features\Identity\Api\ListsParties;
use App\Features\Identity\Api\ResolvesParties;
use App\Features\Sales\Actions\AcceptQuote;
use App\Features\Sales\EntryPoints\Filament\QuoteResource\Pages;
use App\Features\Sales\Models\Quote;
use App\Shared\Filament\HasResourcePermissions;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QuoteResource extends Resource
{
    use HasResourcePermissions;

    protected static string $permission = 'quotes.manage';

    protected static ?string $model = Quote::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|\UnitEnum|null $navigationGroup = 'Sales Pipeline';

    protected static ?int $navigationSort = 8;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('enquiry_id')->relationship('enquiry', 'subject')->searchable()->preload(),
            Select::make('campaign_id')->label('Campaign')->options(fn (): array => app(ListsCampaigns::class)->options())->searchable(),
            Select::make('seller_party_id')->options(fn (): array => app(ListsParties::class)->options())->searchable()->required(),
            Select::make('buyer_party_id')->options(fn (): array => app(ListsParties::class)->options())->searchable()->required(),
            TextInput::make('quote_number')->required()->maxLength(255),
            TextInput::make('currency_code')->maxLength(3)->default('MYR'),
            Select::make('status')->options(['requested' => 'requested', 'draft' => 'draft', 'sent' => 'sent', 'accepted' => 'accepted', 'declined' => 'declined', 'expired' => 'expired'])->required(),
            Select::make('price_visibility')->options(['private' => 'private', 'public' => 'public'])->required(),
            DatePicker::make('valid_until'),
            TextInput::make('subtotal_amount')->numeric()->prefix('RM')->minValue(0),
            Textarea::make('notes')->rows(4)->columnSpanFull(),
            DateTimePicker::make('sent_at'),
            DateTimePicker::make('accepted_at'),
            DateTimePicker::make('declined_at'),
            DateTimePicker::make('expired_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('quote_number')->sortable()->searchable(),
            TextColumn::make('buyer_name')->label('Buyer')->state(fn (Quote $record): ?string => $record->buyer_party_id === null ? null : app(ResolvesParties::class)->displayName($record->buyer_party_id)),
            TextColumn::make('status')->sortable()->searchable(),
            TextColumn::make('price_visibility')->sortable(),
            TextColumn::make('valid_until')->sortable(),
            TextColumn::make('subtotal_amount')->sortable(),
            TextColumn::make('created_at')->sortable(),
        ])->recordActions([
            Action::make('accept')
                ->label('Accept & create order')
                ->icon('heroicon-o-check-circle')
                ->requiresConfirmation()
                ->visible(fn (Quote $record): bool => $record->order_id === null && ! in_array($record->status, ['declined', 'expired'], true))
                ->action(function (Quote $record, AcceptQuote $acceptQuote): void {
                    $actor = auth()->user();

                    if ($actor === null || $actor->party_id === null) {
                        throw new \RuntimeException('Only an authenticated staff user may accept a quote.');
                    }

                    $acceptQuote->execute($record->id, $actor->party_id);
                })
                ->successNotificationTitle('Order created'),
            EditAction::make(),
        ])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecords::route('/'),
            'create' => Pages\CreateRecord::route('/create'),
            'edit' => Pages\EditRecord::route('/{record}'),
        ];
    }
}
