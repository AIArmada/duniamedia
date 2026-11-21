<?php

declare(strict_types=1);

namespace App\Features\Identity;

use App\Features\Identity\Actions\CreateParty;
use App\Features\Identity\Api\AuthorizesActors;
use App\Features\Identity\Api\ListsParties;
use App\Features\Identity\Api\ManagesParties;
use App\Features\Identity\Api\ResolvesParties;
use App\Features\Identity\Models\Party;
use App\Features\Identity\Models\User;
use App\Features\Identity\Queries\AuthorizeActors;
use App\Features\Identity\Queries\FindParties;
use App\Features\Identity\Queries\ListParties;
use App\Platform\FeatureModule;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;

final class FeatureServiceProvider extends FeatureModule
{
    public function register(): void
    {
        $this->app->bind(ResolvesParties::class, FindParties::class);
        $this->app->bind(ManagesParties::class, CreateParty::class);
        $this->app->bind(ListsParties::class, ListParties::class);
        $this->app->bind(AuthorizesActors::class, AuthorizeActors::class);

        Auth::provider('identity', fn ($app): EloquentUserProvider => new EloquentUserProvider(
            $app['hash'],
            User::class,
        ));

        config([
            'auth.providers.users.model' => User::class,
            'cashier.models.billable' => config('cashier.models.billable') ?: User::class,
        ]);
    }

    public function boot(): void
    {
        parent::boot();

        Relation::morphMap([
            'party' => Party::class,
        ]);
    }
}
