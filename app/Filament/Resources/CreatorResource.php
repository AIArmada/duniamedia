<?php
namespace App\Filament\Resources;
use App\Models\Creator;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms\Components\{TextInput,Textarea,Select,Toggle,FileUpload,DateTimePicker};
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use App\Filament\Resources\CreatorResource\Pages;
class CreatorResource extends Resource {
 protected static ?string $model=Creator::class;
 protected static string | \BackedEnum | null $navigationIcon='heroicon-o-user-group';
 protected static string | \UnitEnum | null $navigationGroup='Creator Network';
 protected static ?int $navigationSort=2;
 public static function form(Schema $schema):Schema {return $schema->components([
                 TextInput::make('name')->required()->maxLength(255),
                 TextInput::make('slug')->required()->maxLength(255),
                 TextInput::make('handle')->maxLength(255),
                 Select::make('platform')->options(['TikTok'=>'TikTok','Instagram'=>'Instagram','YouTube'=>'YouTube','Facebook'=>'Facebook','Podcast'=>'Podcast','Other'=>'Other']),
                 Select::make('niche')->options(['Travel'=>'Travel','Food'=>'Food','Technology'=>'Technology','Family'=>'Family','Beauty'=>'Beauty','Finance'=>'Finance','Lifestyle'=>'Lifestyle','Sports'=>'Sports','Education'=>'Education','Other'=>'Other']),
                 Select::make('location')->options(['Nationwide'=>'Nationwide','Klang Valley'=>'Klang Valley','Johor'=>'Johor','Penang'=>'Penang','Sabah & Sarawak'=>'Sabah & Sarawak','Other'=>'Other']),
                 Textarea::make('bio')->rows(4)->columnSpanFull(),
                 FileUpload::make('image')->image()->disk('public')->directory('duniamedia/creator'),
                 TextInput::make('followers')->numeric(),
                 TextInput::make('average_views')->numeric(),
                 TextInput::make('engagement_rate')->numeric()->minValue(0)->maxValue(100),
                 TextInput::make('rate_from')->numeric()->prefix('RM')->minValue(0),
                 Select::make('pricing_mode')->options(['on_request'=>'on_request','indicative'=>'indicative','fixed'=>'fixed','negotiated'=>'negotiated']),
                 DateTimePicker::make('verified_at'),
                 Select::make('status')->options(['pending'=>'pending','active'=>'active','paused'=>'paused','rejected'=>'rejected']),
                 Toggle::make('is_featured')
 ]);}
 public static function table(Table $table):Table {return $table->columns([
                 TextColumn::make('name')->sortable()->searchable(),
                 TextColumn::make('platform')->sortable()->searchable(),
                 TextColumn::make('niche')->sortable()->searchable(),
                 TextColumn::make('status')->sortable()->searchable(),
                 TextColumn::make('is_featured')->sortable()->searchable()
 ])->recordActions([EditAction::make()])->defaultSort('id','desc');}
 public static function getPages():array {return ['index'=>Pages\ListRecords::route('/'),'create'=>Pages\CreateRecord::route('/create'),'edit'=>Pages\EditRecord::route('/{record}')];}
}
