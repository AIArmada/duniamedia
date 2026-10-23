<?php
namespace App\Filament\Resources;
use App\Models\Lead;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms\Components\{TextInput,Textarea,Select,Toggle,FileUpload,DateTimePicker};
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use App\Filament\Resources\LeadResource\Pages;
class LeadResource extends Resource {
 protected static ?string $model=Lead::class;
 protected static string | \BackedEnum | null $navigationIcon='heroicon-o-inbox';
 protected static string | \UnitEnum | null $navigationGroup='Sales Pipeline';
 protected static ?int $navigationSort=7;
 public static function form(Schema $schema):Schema {return $schema->components([
                 TextInput::make('name')->required()->maxLength(255),
                 TextInput::make('email')->email()->required()->maxLength(255),
                 TextInput::make('phone')->maxLength(255),
                 TextInput::make('company')->maxLength(255),
                 TextInput::make('source')->maxLength(255),
                 TextInput::make('objective')->maxLength(255),
                 TextInput::make('budget')->maxLength(255),
                 TextInput::make('location')->maxLength(255),
                 Textarea::make('message')->rows(4)->columnSpanFull(),
                 Select::make('status')->options(['new'=>'new','contacted'=>'contacted','qualified'=>'qualified','proposal'=>'proposal','won'=>'won','lost'=>'lost','closed'=>'closed'])
 ]);}
 public static function table(Table $table):Table {return $table->columns([
                 TextColumn::make('name')->sortable()->searchable(),
                 TextColumn::make('company')->sortable()->searchable(),
                 TextColumn::make('email')->sortable()->searchable(),
                 TextColumn::make('source')->sortable()->searchable(),
                 TextColumn::make('status')->sortable()->searchable(),
                 TextColumn::make('created_at')->sortable()->searchable()
 ])->recordActions([EditAction::make()])->defaultSort('id','desc');}
 public static function getPages():array {return ['index'=>Pages\ListRecords::route('/'),'create'=>Pages\CreateRecord::route('/create'),'edit'=>Pages\EditRecord::route('/{record}')];}
}
