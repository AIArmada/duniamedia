<?php
namespace App\Filament\Resources;
use App\Models\MediaListing;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms\Components\{TextInput,Textarea,Select,Toggle,FileUpload,DateTimePicker};
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use App\Filament\Resources\MediaListingResource\Pages;
class MediaListingResource extends Resource {
 protected static ?string $model=MediaListing::class;
 protected static string | \BackedEnum | null $navigationIcon='heroicon-o-rectangle-stack';
 protected static string | \UnitEnum | null $navigationGroup='Inventory';
 protected static ?int $navigationSort=1;
 public static function form(Schema $schema):Schema {return $schema->components([
                 TextInput::make('title')->required()->maxLength(255),
                 TextInput::make('slug')->required()->maxLength(255),
                 Select::make('type')->options(['TV & Broadcast'=>'TV & Broadcast','Radio'=>'Radio','Outdoor'=>'Outdoor','Indoor'=>'Indoor','Digital'=>'Digital','Creators'=>'Creators','Print'=>'Print','Podcast'=>'Podcast']),
                 TextInput::make('channel')->maxLength(255),
                 Select::make('location')->options(['Nationwide'=>'Nationwide','Klang Valley'=>'Klang Valley','Johor'=>'Johor','Penang'=>'Penang','Sabah & Sarawak'=>'Sabah & Sarawak','Other'=>'Other']),
                 TextInput::make('audience')->maxLength(255),
                 Textarea::make('summary')->required()->rows(4)->columnSpanFull(),
                 Textarea::make('description')->rows(4)->columnSpanFull(),
                 FileUpload::make('image')->image()->disk('public')->directory('duniamedia/medialisting'),
                 TextInput::make('price_from')->numeric()->prefix('RM')->minValue(0),
                 Select::make('pricing_mode')->options(['on_request'=>'on_request','indicative'=>'indicative','fixed'=>'fixed','negotiated'=>'negotiated']),
                 Select::make('availability_status')->options(['on_request'=>'on_request','available'=>'available','limited'=>'limited','unavailable'=>'unavailable']),
                 DateTimePicker::make('verified_at'),
                 TextInput::make('supplier_name')->maxLength(255),
                 Toggle::make('is_featured'),
                 Toggle::make('is_published')
 ]);}
 public static function table(Table $table):Table {return $table->columns([
                 TextColumn::make('title')->sortable()->searchable(),
                 TextColumn::make('type')->sortable()->searchable(),
                 TextColumn::make('location')->sortable()->searchable(),
                 TextColumn::make('pricing_mode')->sortable()->searchable(),
                 TextColumn::make('availability_status')->sortable()->searchable(),
                 TextColumn::make('is_published')->sortable()->searchable()
 ])->recordActions([EditAction::make()])->defaultSort('id','desc');}
 public static function getPages():array {return ['index'=>Pages\ListRecords::route('/'),'create'=>Pages\CreateRecord::route('/create'),'edit'=>Pages\EditRecord::route('/{record}')];}
}
