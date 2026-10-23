<?php
namespace App\Filament\Resources;
use App\Models\Training;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms\Components\{TextInput,Textarea,Select,Toggle,FileUpload,DateTimePicker};
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use App\Filament\Resources\TrainingResource\Pages;
class TrainingResource extends Resource {
 protected static ?string $model=Training::class;
 protected static string | \BackedEnum | null $navigationIcon='heroicon-o-academic-cap';
 protected static string | \UnitEnum | null $navigationGroup='Content';
 protected static ?int $navigationSort=6;
 public static function form(Schema $schema):Schema {return $schema->components([
                 TextInput::make('title')->required()->maxLength(255),
                 TextInput::make('slug')->required()->maxLength(255),
                 TextInput::make('category')->maxLength(255),
                 Textarea::make('summary')->required()->rows(4)->columnSpanFull(),
                 Textarea::make('description')->rows(4)->columnSpanFull(),
                 TextInput::make('format')->maxLength(255),
                 TextInput::make('duration')->maxLength(255),
                 FileUpload::make('image')->image()->disk('public')->directory('duniamedia/training'),
                 Toggle::make('is_published')
 ]);}
 public static function table(Table $table):Table {return $table->columns([
                 TextColumn::make('title')->sortable()->searchable(),
                 TextColumn::make('category')->sortable()->searchable(),
                 TextColumn::make('format')->sortable()->searchable(),
                 TextColumn::make('is_published')->sortable()->searchable()
 ])->recordActions([EditAction::make()])->defaultSort('id','desc');}
 public static function getPages():array {return ['index'=>Pages\ListRecords::route('/'),'create'=>Pages\CreateRecord::route('/create'),'edit'=>Pages\EditRecord::route('/{record}')];}
}
