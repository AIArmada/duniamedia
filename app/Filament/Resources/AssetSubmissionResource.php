<?php
namespace App\Filament\Resources;
use App\Models\AssetSubmission;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms\Components\{TextInput,Textarea,Select,Toggle,FileUpload,DateTimePicker};
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use App\Filament\Resources\AssetSubmissionResource\Pages;
class AssetSubmissionResource extends Resource {
 protected static ?string $model=AssetSubmission::class;
 protected static string | \BackedEnum | null $navigationIcon='heroicon-o-building-office-2';
 protected static string | \UnitEnum | null $navigationGroup='Submissions';
 protected static ?int $navigationSort=11;
 public static function form(Schema $schema):Schema {return $schema->components([
                 TextInput::make('name')->required()->maxLength(255),
                 TextInput::make('email')->email()->required()->maxLength(255),
                 TextInput::make('phone')->maxLength(255),
                 TextInput::make('company')->maxLength(255),
                 TextInput::make('asset_type')->maxLength(255),
                 TextInput::make('location')->maxLength(255),
                 FileUpload::make('image')->image()->disk('public')->directory('asset-submissions'),
                 Textarea::make('description')->rows(4)->columnSpanFull(),
                 Select::make('status')->options(['new'=>'new','contacted'=>'contacted','qualified'=>'qualified','proposal'=>'proposal','won'=>'won','lost'=>'lost','closed'=>'closed'])
 ]);}
 public static function table(Table $table):Table {return $table->columns([
                 TextColumn::make('name')->sortable()->searchable(),
                 TextColumn::make('company')->sortable()->searchable(),
                 TextColumn::make('asset_type')->sortable()->searchable(),
                 TextColumn::make('location')->sortable()->searchable(),
                 TextColumn::make('status')->sortable()->searchable(),
                 TextColumn::make('created_at')->sortable()->searchable()
 ])->recordActions([EditAction::make()])->defaultSort('id','desc');}
 public static function getPages():array {return ['index'=>Pages\ListRecords::route('/'),'create'=>Pages\CreateRecord::route('/create'),'edit'=>Pages\EditRecord::route('/{record}')];}
}
