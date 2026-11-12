<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissions;
use App\Filament\Resources\CreatorProfileResource\Pages;
use App\Models\CreatorProfile;
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

class CreatorProfileResource extends Resource
{
    use HasResourcePermissions;

    protected static string $permission = 'media.manage';

    protected static ?string $model = CreatorProfile::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static string|\UnitEnum|null $navigationGroup = 'Creator Network';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('creator_party_id')->relationship('creatorParty', 'display_name')->searchable()->preload()->required(),
            Select::make('managed_by_party_id')->relationship('managedBy', 'display_name')->searchable()->preload(),
            TextInput::make('creator_type')->default('creator')->required(),
            TextInput::make('slug')->required()->maxLength(255),
            TextInput::make('primary_niche')->maxLength(100),
            Select::make('platform_label')->options(['TikTok' => 'TikTok', 'Instagram' => 'Instagram', 'YouTube' => 'YouTube', 'Facebook' => 'Facebook', 'Podcast' => 'Podcast', 'Other' => 'Other']),
            TextInput::make('handle')->maxLength(255),
            Textarea::make('bio')->rows(4)->columnSpanFull(),
            TextInput::make('country_code')->maxLength(2)->default('MY'),
            TextInput::make('location_label')->maxLength(255),
            Select::make('verification_status')->options(['pending' => 'pending', 'verified' => 'verified', 'rejected' => 'rejected']),
            Select::make('status')->options(['pending' => 'pending', 'active' => 'active', 'paused' => 'paused', 'rejected' => 'rejected']),
            DateTimePicker::make('verified_at'),
            Toggle::make('is_featured'),
            SpatieMediaLibraryFileUpload::make('image')->collection('image')->image(),
            Select::make('pricing_mode')->options(['on_request' => 'on_request', 'indicative' => 'indicative', 'fixed' => 'fixed', 'negotiated' => 'negotiated']),
            TextInput::make('rate_from')->numeric()->prefix('RM')->minValue(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('creatorParty.display_name')->label('Creator')->sortable()->searchable(),
            TextColumn::make('platform_label')->sortable()->searchable(),
            TextColumn::make('primary_niche')->sortable()->searchable(),
            TextColumn::make('verification_status')->sortable()->searchable(),
            TextColumn::make('status')->sortable()->searchable(),
            TextColumn::make('is_featured')->sortable(),
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
