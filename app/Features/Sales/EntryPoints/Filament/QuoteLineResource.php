<?php

declare(strict_types=1);

namespace App\Features\Sales\EntryPoints\Filament;

use App\Features\Sales\EntryPoints\Filament\QuoteLineResource\Pages;
use App\Features\Sales\Models\QuoteLine;
use App\Shared\Filament\HasResourcePermissions;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QuoteLineResource extends Resource
{
    use HasResourcePermissions;

    protected static string $permission = 'quotes.manage';

    protected static ?string $model = QuoteLine::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-list-bullet';

    protected static string|\UnitEnum|null $navigationGroup = 'Sales Pipeline';

    protected static ?int $navigationSort = 9;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('quote_id')->relationship('quote', 'quote_number')->searchable()->preload()->required(),
            Select::make('product_id')->relationship('product', 'name')->searchable()->preload(),
            Select::make('kind')->options(['media' => 'media', 'addon' => 'addon'])->required(),
            TextInput::make('unit_code')->maxLength(255),
            Textarea::make('description')->required()->rows(3)->columnSpanFull(),
            TextInput::make('quantity')->numeric()->default(1),
            TextInput::make('unit_price')->numeric()->prefix('RM')->minValue(0),
            TextInput::make('line_total')->numeric()->prefix('RM')->minValue(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('quote.quote_number')->label('Quote')->sortable()->searchable(),
            TextColumn::make('description')->sortable()->searchable(),
            TextColumn::make('kind')->sortable(),
            TextColumn::make('quantity')->sortable(),
            TextColumn::make('unit_price')->sortable(),
            TextColumn::make('line_total')->sortable(),
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
