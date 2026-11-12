<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissions;
use App\Filament\Resources\MediaOfferingResource\Pages;
use App\Models\MediaOffering;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MediaOfferingResource extends Resource
{
    use HasResourcePermissions;

    protected static string $permission = 'media.manage';

    protected static ?string $model = MediaOffering::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string|\UnitEnum|null $navigationGroup = 'Inventory';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('seller_party_id')->relationship('seller', 'display_name')->searchable()->preload()->required(),
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('slug')->required()->maxLength(255),
            TextInput::make('sku')->required()->maxLength(255),
            Select::make('offering_type')->options(self::types())->required(),
            TextInput::make('unit_code')->default('campaign')->required(),
            Textarea::make('summary')->required()->rows(3)->columnSpanFull(),
            Textarea::make('description')->rows(4)->columnSpanFull(),
            SpatieMediaLibraryFileUpload::make('image')->collection('image')->image(),
            SpatieMediaLibraryFileUpload::make('documents')->collection('documents')->disk('media-private')->multiple(),
            Select::make('pricing_mode')->options(['on_request' => 'on_request', 'indicative' => 'indicative', 'fixed' => 'fixed', 'negotiated' => 'negotiated'])->required(),
            TextInput::make('price_from')->numeric()->prefix('RM')->minValue(0),
            Select::make('availability_status')->options(['on_request' => 'on_request', 'available' => 'available', 'limited' => 'limited', 'unavailable' => 'unavailable']),
            TextInput::make('channel_label')->maxLength(255),
            TextInput::make('location_label')->maxLength(255),
            TextInput::make('audience_label')->maxLength(255),
            Toggle::make('is_featured'),
            Select::make('status')->options(['draft' => 'draft', 'published' => 'published', 'archived' => 'archived'])->required(),
            DateTimePicker::make('published_at'),
            Textarea::make('specifications')
                ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT) : $state)
                ->dehydrateStateUsing(fn ($state) => is_string($state) && trim($state) !== '' ? json_decode($state, true) : null)
                ->rows(8)
                ->columnSpanFull(),
            TextInput::make('sort_order')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->sortable()->searchable(),
            TextColumn::make('offering_type')->sortable()->searchable(),
            TextColumn::make('seller.display_name')->label('Seller')->sortable()->searchable(),
            TextColumn::make('channel_label')->sortable()->searchable(),
            TextColumn::make('pricing_mode')->sortable(),
            TextColumn::make('price_from')->sortable(),
            TextColumn::make('status')->sortable()->searchable(),
            TextColumn::make('is_featured')->sortable(),
        ])->recordActions([EditAction::make()])->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecords::route('/'),
            'create' => Pages\CreateRecord::route('/create'),
            'edit' => Pages\EditRecord::route('/{record}'),
        ];
    }

    public static function types(): array
    {
        return [
            'TV & Broadcast' => 'TV & Broadcast',
            'Radio' => 'Radio',
            'Outdoor' => 'Outdoor',
            'Indoor' => 'Indoor',
            'Digital' => 'Digital',
            'Creators' => 'Creators',
            'Print' => 'Print',
            'Podcast' => 'Podcast',
        ];
    }
}
