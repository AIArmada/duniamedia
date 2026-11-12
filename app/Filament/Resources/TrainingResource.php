<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissions;
use App\Filament\Resources\TrainingResource\Pages;
use App\Models\Training;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TrainingResource extends Resource
{
    use HasResourcePermissions;

    protected static string $permission = 'content.manage';

    protected static ?string $model = Training::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->required()->maxLength(255),
            TextInput::make('slug')->required()->maxLength(255),
            TextInput::make('category')->maxLength(255),
            Textarea::make('summary')->required()->rows(4)->columnSpanFull(),
            Textarea::make('description')->rows(4)->columnSpanFull(),
            TextInput::make('format')->maxLength(255),
            TextInput::make('duration')->maxLength(255),
            SpatieMediaLibraryFileUpload::make('image')->collection('image')->image(),
            Select::make('status')->options(['draft' => 'draft', 'published' => 'published', 'archived' => 'archived'])->required(),
            DateTimePicker::make('published_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('title')->sortable()->searchable(),
            TextColumn::make('category')->sortable()->searchable(),
            TextColumn::make('format')->sortable()->searchable(),
            TextColumn::make('status')->sortable()->searchable(),
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
