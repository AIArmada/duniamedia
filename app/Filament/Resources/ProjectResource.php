<?php
namespace App\Filament\Resources;
use App\Models\Project;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms\Components\{TextInput,Textarea,Select,Toggle,FileUpload,DateTimePicker};
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use App\Filament\Resources\ProjectResource\Pages;
class ProjectResource extends Resource {
 protected static ?string $model=Project::class;
 protected static string | \BackedEnum | null $navigationIcon='heroicon-o-photo';
 protected static string | \UnitEnum | null $navigationGroup='Content';
 protected static ?int $navigationSort=4;
 public static function form(Schema $schema):Schema {return $schema->components([
                 TextInput::make('title')->required()->maxLength(255),
                 TextInput::make('slug')->required()->maxLength(255),
                 TextInput::make('category')->maxLength(255),
                 TextInput::make('client_name')->maxLength(255),
                 Textarea::make('summary')->required()->rows(4)->columnSpanFull(),
                 Textarea::make('description')->rows(4)->columnSpanFull(),
                 FileUpload::make('image')->image()->disk('public')->directory('duniamedia/project'),
                 Toggle::make('is_concept'),
                 Toggle::make('is_published'),
                 TextInput::make('sort_order')->numeric()
 ]);}
 public static function table(Table $table):Table {return $table->columns([
                 TextColumn::make('title')->sortable()->searchable(),
                 TextColumn::make('category')->sortable()->searchable(),
                 TextColumn::make('client_name')->sortable()->searchable(),
                 TextColumn::make('is_concept')->sortable()->searchable(),
                 TextColumn::make('is_published')->sortable()->searchable()
 ])->recordActions([EditAction::make()])->defaultSort('id','desc');}
 public static function getPages():array {return ['index'=>Pages\ListRecords::route('/'),'create'=>Pages\CreateRecord::route('/create'),'edit'=>Pages\EditRecord::route('/{record}')];}
}
