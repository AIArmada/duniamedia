<?php

namespace App\Filament\Resources;

use AIArmada\Products\Enums\ProductStatus;
use AIArmada\Products\Enums\ProductType;
use AIArmada\Products\Enums\ProductVisibility;
use AIArmada\Products\Models\Product;
use App\Filament\Concerns\HasResourcePermissions;
use App\Filament\Resources\ProductResource\Pages;
use App\Models\CreatorProfile;
use App\Models\InventoryWindow;
use App\Models\Party;
use App\Models\Placement;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

class ProductResource extends Resource
{
    use HasResourcePermissions;

    protected static string $permission = 'products.manage';

    protected static ?string $model = Product::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cube';

    protected static string|\UnitEnum|null $navigationGroup = 'Catalogue';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Product')
                ->schema([
                    TextInput::make('name')->required()->maxLength(255)->live(onBlur: true),
                    TextInput::make('slug')->required()->maxLength(255),
                    TextInput::make('sku')->maxLength(255),
                    TextInput::make('price')
                        ->numeric()
                        ->prefix('RM')
                        ->step(0.01)
                        ->minValue(0)
                        ->formatStateUsing(fn (?int $state): ?float => $state === null ? null : $state / 100)
                        ->dehydrateStateUsing(fn (?string $state): int => (int) round(((float) $state) * 100))
                        ->helperText('Display only. Real rates belong in price lists; 0 means on request.'),
                    Select::make('type')
                        ->options(collect(ProductType::cases())->mapWithKeys(fn (ProductType $type): array => [$type->value => $type->label()]))
                        ->default(ProductType::Service->value)
                        ->required(),
                    Select::make('status')
                        ->options(collect(ProductStatus::cases())->mapWithKeys(fn (ProductStatus $status): array => [$status->value => $status->label()]))
                        ->default(ProductStatus::Active->value)
                        ->required(),
                    Select::make('visibility')
                        ->options(collect(ProductVisibility::cases())->mapWithKeys(fn (ProductVisibility $visibility): array => [$visibility->value => $visibility->label()]))
                        ->default(ProductVisibility::CatalogSearch->value)
                        ->required(),
                    Toggle::make('is_featured'),
                    DateTimePicker::make('published_at'),
                    Textarea::make('short_description')->rows(2)->columnSpanFull(),
                    Textarea::make('description')->rows(4)->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('Media')
                ->relationship('mediaProfile')
                ->schema([
                    Select::make('media_owner_party_id')
                        ->label('Media owner')
                        ->options(fn (): array => Party::query()->orderBy('display_name')->pluck('display_name', 'id')->all())
                        ->searchable(),
                    Select::make('creator_profile_id')
                        ->label('Creator')
                        ->options(fn (): array => CreatorProfile::query()->with('creatorParty')->get()->mapWithKeys(fn (CreatorProfile $profile): array => [$profile->id => $profile->displayName()])->all())
                        ->searchable(),
                    Select::make('medium')
                        ->options(self::mediumOptions())
                        ->required(),
                    TextInput::make('unit_code')->required()->maxLength(255),
                    Select::make('pricing_mode')
                        ->options(['on_request' => 'On request', 'indicative' => 'Indicative', 'fixed' => 'Fixed', 'negotiated' => 'Negotiated'])
                        ->required(),
                    Select::make('availability_status')
                        ->options(['on_request' => 'On request', 'available' => 'Available', 'limited' => 'Limited', 'unavailable' => 'Unavailable']),
                    TextInput::make('price_from')->numeric()->prefix('RM')->minValue(0),
                    TextInput::make('channel_label')->maxLength(255),
                    TextInput::make('location_label')->maxLength(255),
                    TextInput::make('audience_label')->maxLength(255),
                    Select::make('placement_id')
                        ->label('Placement')
                        ->options(fn (): array => Placement::query()->orderBy('name')->pluck('name', 'id')->all())
                        ->searchable(),
                    Select::make('inventory_window_id')
                        ->label('Inventory window')
                        ->options(fn (): array => self::windowOptions())
                        ->searchable(),
                    DateTimePicker::make('verified_at'),
                    TextInput::make('sort_order')->numeric(),
                    Toggle::make('show_rate_card'),
                    Textarea::make('terms')->rows(3)->columnSpanFull(),
                    Textarea::make('specifications')
                        ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT) : $state)
                        ->dehydrateStateUsing(fn ($state) => is_string($state) && trim($state) !== '' ? json_decode($state, true) : null)
                        ->rows(8)
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('Documents')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('hero')
                        ->collection('hero')
                        ->image()
                        ->label('Hero image'),
                    SpatieMediaLibraryFileUpload::make('documents')
                        ->collection('documents')
                        ->disk('media-private')
                        ->acceptedFileTypes(['application/pdf'])
                        ->multiple()
                        ->label('Rate-card PDFs'),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('hero')
                    ->collection('hero')
                    ->conversion('thumbnail')
                    ->circular()
                    ->size(40),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Product $record): ?string => $record->sku),
                TextColumn::make('mediaProfile.medium')
                    ->label('Medium')
                    ->badge()
                    ->sortable(),
                TextColumn::make('mediaProfile.pricing_mode')
                    ->label('Pricing')
                    ->badge(),
                TextColumn::make('mediaProfile.price_from')
                    ->label('From')
                    ->formatStateUsing(fn ($state): string => $state === null ? '—' : 'RM '.number_format((float) $state, 2)),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (ProductStatus $state): string => $state->label()),
                IconColumn::make('is_featured')->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function mediumOptions(): array
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

    private static function windowOptions(): array
    {
        return InventoryWindow::query()
            ->orderBy('starts_at')
            ->get()
            ->mapWithKeys(function (InventoryWindow $window): array {
                $starts = $window->starts_at ? Carbon::parse($window->starts_at)->format('Y-m-d') : '?';
                $ends = $window->ends_at ? Carbon::parse($window->ends_at)->format('Y-m-d') : '?';

                return [$window->id => "{$starts} → {$ends}"];
            })
            ->all();
    }
}
