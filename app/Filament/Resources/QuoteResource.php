<?php
namespace App\Filament\Resources;
use App\Models\Quote;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms\Components\{TextInput,Textarea,Select,Toggle,FileUpload,DateTimePicker};
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use App\Filament\Resources\QuoteResource\Pages;
class QuoteResource extends Resource {
 protected static ?string $model=Quote::class;
 protected static string | \BackedEnum | null $navigationIcon='heroicon-o-document-text';
 protected static string | \UnitEnum | null $navigationGroup='Sales Pipeline';
 protected static ?int $navigationSort=8;
 public static function form(Schema $schema):Schema {return $schema->components([
                 TextInput::make('reference')->required()->maxLength(255),
                 Select::make('lead_id')->relationship('lead','name')->searchable()->preload(),
                 Select::make('status')->options(['requested'=>'requested','draft'=>'draft','sent'=>'sent','accepted'=>'accepted','declined'=>'declined','expired'=>'expired']),
                 Select::make('price_visibility')->options(['private'=>'private','public'=>'public','on_request'=>'on_request','indicative'=>'indicative','fixed'=>'fixed']),
                 TextInput::make('subtotal_estimate')->numeric()->prefix('RM')->minValue(0),
                 Textarea::make('notes')->rows(4)->columnSpanFull(),
                 DateTimePicker::make('expires_at')
 ]);}
 public static function table(Table $table):Table {return $table->columns([
                 TextColumn::make('reference')->sortable()->searchable(),
                 TextColumn::make('lead.name')->sortable()->searchable(),
                 TextColumn::make('status')->sortable()->searchable(),
                 TextColumn::make('price_visibility')->sortable()->searchable(),
                 TextColumn::make('subtotal_estimate')->sortable()->searchable(),
                 TextColumn::make('created_at')->sortable()->searchable()
 ])->recordActions([EditAction::make()])->defaultSort('id','desc');}
 public static function getPages():array {return ['index'=>Pages\ListRecords::route('/'),'create'=>Pages\CreateRecord::route('/create'),'edit'=>Pages\EditRecord::route('/{record}')];}
}
