# AIArmada/commerce package fit — source reviewed 23 October 2026

Repository: https://github.com/AIArmada/commerce (`main`, public source). The current monorepo has `packages/links`, `filament-links`, `docs`, `filament-docs`, `contacting`, `affiliate-network`, `filament-affiliate-network`, `cart`, `pricing` and other modules.

### Recommended selective adoption (rather than the whole suite)

1. **`aiarmada/links` + `aiarmada/filament-links`** — for unique tracked URLs, clicks, expiry and campaign-to-conversion attribution. Strongest first integration to support QR campaigns and creator referrals.
2. **`aiarmada/docs` + `aiarmada/filament-docs`** — potentially for managed quotation/proposal PDF issuance once commercial pricing workflow is agreed.
3. **`aiarmada/contacting`** — potentially for reusable contact/social profiles for media owners, influencers and suppliers.
4. **`aiarmada/affiliate-network`** — consider after defining creator referral programmes and conversion ledger. It is more complex than needed for phase one.

### Version caveat
The **repository's current main** package composer manifests require `php: ^8.5` and dev-main dependencies in several modules. A prior Packagist tagged release may advertise PHP 8.4 compatibility, but should not be assumed API-compatible with current main. Confirm package resolution on a PHP 8.5 staging machine and lock tested versions in `composer.lock` before including in the production project.

### Optional staging installation (only after PHP 8.5 and dependency audit)
```bash
composer require 'aiarmada/links:dev-main' 'aiarmada/filament-links:dev-main' -W
# inspect published config/migrations and register Filament plugin as directed by exact installed version
# later: composer require 'aiarmada/docs:dev-main' 'aiarmada/filament-docs:dev-main' -W
```

Do not copy package migrations blindly into local tables or duplicate proprietary logic. Use an adapter/service layer after verifying actual package APIs. **No AiArmada package was bundled or falsely represented as installed.**
