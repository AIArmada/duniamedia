<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissions;
use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
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

class ServiceResource extends Resource
{
    use HasResourcePermissions;

    protected static string $permission = 'content.manage';

    protected static ?string $model = Service::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('service_code')->required()->maxLength(255),
            TextInput::make('slug')->required()->maxLength(255),
            TextInput::make('category')->maxLength(255),
            Textarea::make('summary')->required()->rows(4)->columnSpanFull(),
            Textarea::make('description')->rows(4)->columnSpanFull(),
            SpatieMediaLibraryFileUpload::make('image')->collection('image')->image(),
            Toggle::make('is_featured'),
            Select::make('status')->options(['draft' => 'draft', 'published' => 'published', 'archived' => 'archived'])->required(),
            DateTimePicker::make('published_at'),
            TextInput::make('sort_order')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->sortable()->searchable(),
            TextColumn::make('category')->sortable()->searchable(),
            TextColumn::make('status')->sortable()->searchable(),
            TextColumn::make('sort_order')->sortable(),
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
