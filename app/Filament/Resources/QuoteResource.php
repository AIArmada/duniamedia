<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissions;
use App\Filament\Resources\QuoteResource\Pages;
use App\Models\Quote;
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
            Select::make('campaign_id')->relationship('campaign', 'name')->searchable()->preload(),
            Select::make('seller_party_id')->relationship('seller', 'display_name')->searchable()->preload()->required(),
            Select::make('buyer_party_id')->relationship('buyer', 'display_name')->searchable()->preload()->required(),
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
            TextColumn::make('buyer.display_name')->label('Buyer')->sortable()->searchable(),
            TextColumn::make('status')->sortable()->searchable(),
            TextColumn::make('price_visibility')->sortable(),
            TextColumn::make('valid_until')->sortable(),
            TextColumn::make('subtotal_amount')->sortable(),
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
}
