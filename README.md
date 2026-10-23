# Dunia Media — RA Media Digital Platform

**Laravel 13 · Livewire 4 · FilamentPHP 5**

A custom application starter for **DuniaMedia.com**, the proposed customer-facing brand of RA Media. Includes responsive homepage, media discovery, rule-based campaign adviser, creator network, media asset submissions, proposal builder, CMS and Filament admin.

> **STATUS: Functional source-code delivery / development starter. NOT tested end-to-end or production-ready.** Composer dependencies and runtime packages are intentionally excluded from the ZIP because the isolated build environment has no Packagist network access or Composer executable. Run the installation steps below on a connected computer/server.

## Requirements
- PHP 8.3+ for the base app; use **PHP 8.5+** if integrating current AiArmada `dev-main` packages
- Composer 2.x, PHP extensions required by Laravel + Filament
- SQLite/PostgreSQL/MySQL
- Node is **not required** for the supplied custom CSS and browser JS. Filament installs its own assets via Composer/Artisan.

## Setup
```bash
unzip DuniaMedia_Laravel13_Full_Source.zip
cd DuniaMedia_Laravel13
composer install
cp .env.example .env
php artisan key:generate
mkdir -p database storage/framework/cache/data storage/framework/views storage/framework/sessions
# SQLite: create database/database.sqlite, or configure PostgreSQL/MySQL in .env
touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link
php artisan filament:assets
php artisan make:filament-user
```

**Important:** after creating an admin user, **set `is_admin = true`** in the `users` table for that account. Otherwise access to `/admin` is denied by design. Example through `php artisan tinker`:

```php
App\Models\User::where('email', 'admin@example.com')->update(['is_admin' => true]);
```

Change `admin@example.com` to your own real account. Run `php artisan serve` and open `http://127.0.0.1:8000` or your local domain; `/admin` is the protected back office.

## Implemented modules
- **Public frontend:** responsive homepage, services, connected solutions, project concepts, academy, insights, about, privacy, terms.
- **Media Explorer (Livewire):** search, types, location, budget, sort, pagination, details. Listings published by admin only.
- **Media Advisor (Livewire):** guided form and deterministic illustrative strategy directions. Saves qualified brief as lead. **No AI model call yet**.
- **Quote Builder (Livewire):** media shortlist, campaign add-ons, contact form, generated internal reference, quote + items saved in transaction. Approved `sent` proposals can be shared with a temporary signed URL; rate display remains private unless admin explicitly sets visibility to `public` and every line has a price. Approved `sent` proposals can be shared with a temporary signed URL; rate display remains private unless admin explicitly sets visibility to `public` and every line has a price. Quote status starts `requested`, visibility `private`; no payment or media reservation.
- **Creator Network (Livewire):** creator directory and application form; verification controlled by staff.
- **Asset Owner Leads (Livewire):** collect potential new media spaces for manual review.
- **Filament 5:** CRUD for media, creators, services, projects, articles, training, leads, quotations, quote items and applications.

## Non-production demonstration data
`php artisan migrate --seed` creates **illustrative data only**: sample listings, fictional `Demo Creator` records, concept portfolio and sample insights. These do **not** imply real RA Media clients, suppliers, availability, verified audience figures, talent agreements or prices. Replace every demo entity before public launch.

## Commercial controls & implementation boundaries
- Supplier slot availability must be verified before publishing `available` status; demo data uses `on_request`.
- Rates are kept `on_request` by default; price approval, commissions, agency fees, discounts and tax need business policies.
- Proposals are internal request records, not official legally binding signed quotations. Final quotation PDF, approval workflow and payment integration are pending.
- **AiArmada integration is optional** and documented in `docs/AIARMADA.md`; no package is pretended installed.
- A production release needs actual legal policies, audit logging, roles/permissions, backups, rate limiting/captcha, anti-abuse, verified content and real media ownership agreements.

## Architecture
See `docs/ARCHITECTURE.md` for entities, workflow and phased implementation scope.

## Testing
`php -l` syntax checks were run on generated PHP files, but the PHP application and migrations could not be executed here because Composer/vendor dependencies were unavailable. After `composer install` run:
```bash
php artisan route:list
php artisan migrate:fresh --seed
php artisan test
```

## Assets
All logos, favicons, WebP images, CSS and JS live under `public/assets/`. They are shipped with the source. The generated imagery is conceptual and not a record of an actual partnership or campaign. Google Fonts are optional remote font imports; replace/remove if fully offline rendering is needed.

## Sharing an approved proposal

1. Admin edits a quote at `/admin` and sets its status to `sent`; optionally sets `price_visibility` to `public` when all item prices are approved.
2. On the server run `php artisan duniamedia:proposal-link DM-...` to get a 7-day signed URL (or until the configured earlier quote expiry).
3. Share the link privately. The client can print/save as PDF. Expired or unsigned links are rejected.

This is a first-pass sharing workflow, not a replacement for legal quotation controls or a document signing engine.

## Sharing an approved proposal

1. Admin edits a quote at `/admin` and sets its status to `sent`; optionally sets `price_visibility` to `public` when all item prices are approved.
2. Run `php artisan duniamedia:proposal-link DM-...` to get a signed URL valid for up to 7 days (or until the configured quote expiry).
3. Share the link privately. The client can print/save as PDF. Expired or unsigned links are rejected.

This is a first-pass sharing workflow, not a replacement for legal quotation controls or a document signing engine.
