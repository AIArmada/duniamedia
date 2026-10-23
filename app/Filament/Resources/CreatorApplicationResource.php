<?php
namespace App\Filament\Resources;
use App\Models\CreatorApplication;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms\Components\{TextInput,Textarea,Select,Toggle,FileUpload,DateTimePicker};
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use App\Filament\Resources\CreatorApplicationResource\Pages;
class CreatorApplicationResource extends Resource {
 protected static ?string $model=CreatorApplication::class;
 protected static string | \BackedEnum | null $navigationIcon='heroicon-o-user-plus';
 protected static string | \UnitEnum | null $navigationGroup='Submissions';
 protected static ?int $navigationSort=10;
 public static function form(Schema $schema):Schema {return $schema->components([
                 TextInput::make('name')->required()->maxLength(255),
                 TextInput::make('email')->email()->required()->maxLength(255),
                 TextInput::make('phone')->maxLength(255),
                 TextInput::make('handle')->maxLength(255),
                 Select::make('platform')->options(['TikTok'=>'TikTok','Instagram'=>'Instagram','YouTube'=>'YouTube','Facebook'=>'Facebook','Podcast'=>'Podcast','Other'=>'Other']),
                 Select::make('niche')->options(['Travel'=>'Travel','Food'=>'Food','Technology'=>'Technology','Family'=>'Family','Beauty'=>'Beauty','Finance'=>'Finance','Lifestyle'=>'Lifestyle','Sports'=>'Sports','Education'=>'Education','Other'=>'Other']),
                 TextInput::make('followers')->numeric(),
                 Textarea::make('message')->rows(4)->columnSpanFull(),
                 Select::make('status')->options(['new'=>'new','contacted'=>'contacted','qualified'=>'qualified','proposal'=>'proposal','won'=>'won','lost'=>'lost','closed'=>'closed'])
 ]);}
 public static function table(Table $table):Table {return $table->columns([
                 TextColumn::make('name')->sortable()->searchable(),
                 TextColumn::make('handle')->sortable()->searchable(),
                 TextColumn::make('platform')->sortable()->searchable(),
                 TextColumn::make('niche')->sortable()->searchable(),
                 TextColumn::make('status')->sortable()->searchable(),
                 TextColumn::make('created_at')->sortable()->searchable()
 ])->recordActions([EditAction::make()])->defaultSort('id','desc');}
 public static function getPages():array {return ['index'=>Pages\ListRecords::route('/'),'create'=>Pages\CreateRecord::route('/create'),'edit'=>Pages\EditRecord::route('/{record}')];}
}
