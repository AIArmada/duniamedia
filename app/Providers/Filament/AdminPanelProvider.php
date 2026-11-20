<?php

namespace App\Providers\Filament;

use AIArmada\FilamentCashier\FilamentCashierPlugin;
use AIArmada\FilamentInventory\FilamentInventoryPlugin;
use AIArmada\FilamentOrders\FilamentOrdersPlugin;
use AIArmada\FilamentPricing\FilamentPricingPlugin;
use AIArmada\FilamentPromotions\FilamentPromotionsPlugin;
use AIArmada\FilamentTax\FilamentTaxPlugin;
use AIArmada\FilamentVouchers\FilamentVouchersPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel->default()->id('admin')->path('admin')->login()
            ->brandName('Dunia Media | RA Media')
            ->brandLogo(asset('assets/brand/logo-horizontal.svg'))
            ->brandLogoHeight('2rem')
            ->favicon(asset('assets/brand/favicon.svg'))
            ->colors(['primary' => Color::hex('#C30866')])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->plugins([
                FilamentOrdersPlugin::make(),
                FilamentPricingPlugin::make(),
                FilamentInventoryPlugin::make(),
                FilamentTaxPlugin::make(),
                FilamentPromotionsPlugin::make(),
                FilamentVouchersPlugin::make(),
                FilamentCashierPlugin::make(),
            ])
            ->pages([Dashboard::class])
            ->middleware([EncryptCookies::class, AddQueuedCookiesToResponse::class, StartSession::class, AuthenticateSession::class, ShareErrorsFromSession::class, VerifyCsrfToken::class, SubstituteBindings::class, DisableBladeIconComponents::class, DispatchServingFilamentEvent::class])
            ->authMiddleware([Authenticate::class]);
    }
}
