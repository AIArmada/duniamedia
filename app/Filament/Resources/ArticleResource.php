<?php
namespace App\Filament\Resources;
use App\Models\Article;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms\Components\{TextInput,Textarea,Select,Toggle,FileUpload,DateTimePicker};
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use App\Filament\Resources\ArticleResource\Pages;
class ArticleResource extends Resource {
 protected static ?string $model=Article::class;
 protected static string | \BackedEnum | null $navigationIcon='heroicon-o-book-open';
 protected static string | \UnitEnum | null $navigationGroup='Content';
 protected static ?int $navigationSort=5;
 public static function form(Schema $schema):Schema {return $schema->components([
                 TextInput::make('title')->required()->maxLength(255),
                 TextInput::make('slug')->required()->maxLength(255),
                 TextInput::make('category')->maxLength(255),
                 Textarea::make('excerpt')->required()->rows(4)->columnSpanFull(),
                 Textarea::make('body')->required()->rows(4)->columnSpanFull(),
                 FileUpload::make('image')->image()->disk('public')->directory('duniamedia/article'),
                 DateTimePicker::make('published_at'),
                 Toggle::make('is_published')
 ]);}
 public static function table(Table $table):Table {return $table->columns([
                 TextColumn::make('title')->sortable()->searchable(),
                 TextColumn::make('category')->sortable()->searchable(),
                 TextColumn::make('is_published')->sortable()->searchable(),
                 TextColumn::make('published_at')->sortable()->searchable()
 ])->recordActions([EditAction::make()])->defaultSort('id','desc');}
 public static function getPages():array {return ['index'=>Pages\ListRecords::route('/'),'create'=>Pages\CreateRecord::route('/create'),'edit'=>Pages\EditRecord::route('/{record}')];}
}
