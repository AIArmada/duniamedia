<?php
namespace App\Filament\Resources;
use App\Models\QuoteItem;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms\Components\{TextInput,Textarea,Select,Toggle,FileUpload,DateTimePicker};
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use App\Filament\Resources\QuoteItemResource\Pages;
class QuoteItemResource extends Resource {
 protected static ?string $model=QuoteItem::class;
 protected static string | \BackedEnum | null $navigationIcon='heroicon-o-list-bullet';
 protected static string | \UnitEnum | null $navigationGroup='Sales Pipeline';
 protected static ?int $navigationSort=9;
 public static function form(Schema $schema):Schema {return $schema->components([
                 Select::make('quote_id')->relationship('quote','reference')->searchable()->preload(),
                 Select::make('media_listing_id')->relationship('mediaListing','title')->searchable()->preload(),
                 TextInput::make('name')->required()->maxLength(255),
                 Select::make('kind')->options(['media'=>'media','addon'=>'addon']),
                 TextInput::make('quantity')->numeric(),
                 TextInput::make('unit_price')->numeric()->prefix('RM')->minValue(0),
                 Select::make('price_visibility')->options(['private'=>'private','public'=>'public','on_request'=>'on_request','indicative'=>'indicative','fixed'=>'fixed'])
 ]);}
 public static function table(Table $table):Table {return $table->columns([
                 TextColumn::make('quote.reference')->sortable()->searchable(),
                 TextColumn::make('name')->sortable()->searchable(),
                 TextColumn::make('kind')->sortable()->searchable(),
                 TextColumn::make('quantity')->sortable()->searchable(),
                 TextColumn::make('unit_price')->sortable()->searchable()
 ])->recordActions([EditAction::make()])->defaultSort('id','desc');}
 public static function getPages():array {return ['index'=>Pages\ListRecords::route('/'),'create'=>Pages\CreateRecord::route('/create'),'edit'=>Pages\EditRecord::route('/{record}')];}
}
