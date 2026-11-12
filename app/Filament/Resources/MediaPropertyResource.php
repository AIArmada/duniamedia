<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissions;
use App\Filament\Resources\MediaPropertyResource\Pages;
use App\Models\MediaProperty;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MediaPropertyResource extends Resource
{
    use HasResourcePermissions;

    protected static string $permission = 'media.manage';

    protected static ?string $model = MediaProperty::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static string|\UnitEnum|null $navigationGroup = 'Inventory';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('owner_party_id')->relationship('owner', 'display_name')->searchable()->preload(),
            Select::make('property_type')->options(['outlet' => 'outlet', 'channel' => 'channel', 'publication' => 'publication', 'website' => 'website', 'social_account' => 'social_account', 'network' => 'network', 'prospect' => 'prospect'])->required(),
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('slug')->required()->maxLength(255),
            TextInput::make('url')->maxLength(255),
            Select::make('status')->options(['active' => 'active', 'inactive' => 'inactive', 'prospect' => 'prospect', 'archived' => 'archived'])->required(),
            Textarea::make('description')->rows(4)->columnSpanFull(),
            SpatieMediaLibraryFileUpload::make('logo')->collection('logo')->image(),
            TextInput::make('brand_mark')->maxLength(255),
            TextInput::make('accent_color')->maxLength(16),
            TextInput::make('sort_order')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->sortable()->searchable(),
            TextColumn::make('owner.display_name')->label('Owner')->sortable()->searchable(),
            TextColumn::make('property_type')->sortable()->searchable(),
            TextColumn::make('status')->sortable()->searchable(),
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
}
