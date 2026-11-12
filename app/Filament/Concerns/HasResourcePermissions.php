<?php

namespace App\Filament\Concerns;

use Illuminate\Database\Eloquent\Model;

trait HasResourcePermissions
{
    public static function canViewAny(): bool
    {
        return static::allows();
    }

    public static function canCreate(): bool
    {
        return static::allows();
    }

    public static function canEdit(Model $record): bool
    {
        return static::allows();
    }

    public static function canDelete(Model $record): bool
    {
        return static::allows();
    }

    protected static function allows(): bool
    {
        $user = auth()->user();

        return $user !== null && $user->can(static::$permission);
    }
}
