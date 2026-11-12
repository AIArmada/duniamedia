<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissions;
use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProjectResource extends Resource
{
    use HasResourcePermissions;

    protected static string $permission = 'content.manage';

    protected static ?string $model = Project::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-photo';

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('client_party_id')->relationship('client', 'display_name')->searchable()->preload(),
            TextInput::make('project_name')->required()->maxLength(255),
            TextInput::make('project_code')->required()->maxLength(255),
            TextInput::make('slug')->required()->maxLength(255),
            TextInput::make('category')->maxLength(255),
            Textarea::make('summary')->rows(4)->columnSpanFull(),
            Textarea::make('brief')->rows(4)->columnSpanFull(),
            Select::make('status')->options(['active' => 'active', 'concept' => 'concept', 'completed' => 'completed', 'archived' => 'archived'])->required(),
            DatePicker::make('starts_on'),
            DatePicker::make('ends_on'),
            SpatieMediaLibraryFileUpload::make('image')->collection('image')->image(),
            TextInput::make('sort_order')->numeric(),
            DateTimePicker::make('published_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('project_name')->sortable()->searchable(),
            TextColumn::make('client.display_name')->label('Client')->sortable()->searchable(),
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
