<?php

declare(strict_types=1);

namespace App\Features\Sales\EntryPoints\Filament;

use App\Features\Identity\Api\ListsParties;
use App\Features\Identity\Api\ResolvesParties;
use App\Features\Sales\EntryPoints\Filament\BookingResource\Pages;
use App\Features\Sales\Models\Booking;
use App\Shared\Filament\HasResourcePermissions;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BookingResource extends Resource
{
    use HasResourcePermissions;

    protected static string $permission = 'quotes.manage';

    protected static ?string $model = Booking::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static string|\UnitEnum|null $navigationGroup = 'Sales Pipeline';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('order_id')->relationship('order', 'order_number')->searchable()->preload(),
            Select::make('quote_id')->relationship('quote', 'quote_number')->searchable()->preload(),
            Select::make('buyer_party_id')->options(fn (): array => app(ListsParties::class)->options())->searchable()->required(),
            Select::make('seller_party_id')->options(fn (): array => app(ListsParties::class)->options())->searchable()->required(),
            TextInput::make('booking_number')->required()->maxLength(255),
            Select::make('status')->options(['pending' => 'pending', 'confirmed' => 'confirmed', 'cancelled' => 'cancelled', 'completed' => 'completed'])->required(),
            TextInput::make('currency_code')->maxLength(3)->default('MYR'),
            TextInput::make('net_amount')->numeric()->prefix('RM')->minValue(0),
            DateTimePicker::make('booked_at'),
            DateTimePicker::make('confirmed_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('booking_number')->sortable()->searchable(),
            TextColumn::make('order.order_number')->label('Order')->sortable()->searchable(),
            TextColumn::make('buyer_name')->label('Buyer')->state(fn (Booking $record): ?string => $record->buyer_party_id === null ? null : app(ResolvesParties::class)->displayName($record->buyer_party_id)),
            TextColumn::make('status')->sortable()->searchable(),
            TextColumn::make('net_amount')->sortable(),
            TextColumn::make('booked_at')->dateTime()->sortable(),
        ])->recordActions([EditAction::make()])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
