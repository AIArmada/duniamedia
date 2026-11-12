<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissions;
use App\Filament\Resources\CampaignResource\Pages;
use App\Models\Campaign;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
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

class CampaignResource extends Resource
{
    use HasResourcePermissions;

    protected static string $permission = 'campaigns.manage';

    protected static ?string $model = Campaign::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

    protected static string|\UnitEnum|null $navigationGroup = 'Client Work';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('project_id')->relationship('project', 'project_name')->searchable()->preload(),
            Select::make('client_party_id')->relationship('client', 'display_name')->searchable()->preload()->required(),
            Select::make('advertiser_party_id')->relationship('advertiser', 'display_name')->searchable()->preload()->required(),
            Select::make('agency_party_id')->relationship('agency', 'display_name')->searchable()->preload(),
            Select::make('service_id')->relationship('service', 'name')->searchable()->preload(),
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('campaign_code')->required()->maxLength(255),
            TextInput::make('slug')->required()->maxLength(255),
            Textarea::make('summary')->rows(4)->columnSpanFull(),
            TextInput::make('channel_label')->maxLength(255),
            TextInput::make('platform_label')->maxLength(255),
            Toggle::make('is_featured'),
            SpatieMediaLibraryFileUpload::make('cover')->collection('cover')->image(),
            SpatieMediaLibraryFileUpload::make('media')->collection('media')->multiple(),
            Select::make('status')->options(['draft' => 'draft', 'active' => 'active', 'completed' => 'completed', 'cancelled' => 'cancelled'])->required(),
            DatePicker::make('starts_on'),
            DatePicker::make('ends_on'),
            DateTimePicker::make('published_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->sortable()->searchable(),
            TextColumn::make('client.display_name')->label('Client')->sortable()->searchable(),
            TextColumn::make('service.name')->label('Service')->sortable()->searchable(),
            TextColumn::make('channel_label')->sortable()->searchable(),
            TextColumn::make('starts_on')->sortable(),
            TextColumn::make('status')->sortable(),
            TextColumn::make('is_featured')->sortable(),
        ])->recordActions([EditAction::make()])->defaultSort('starts_on', 'desc');
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
