<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

## Foundational Context

This application is a Laravel application running on PHP 8.5. Always use the APIs that match the installed major version of each package — do not assume a version.

Before relying on a package's API, confirm its installed version:
- PHP packages: run `composer show --direct` to list direct dependencies with versions, or `composer show <vendor/package>` for a single package.
- JS packages: check `package.json` for the installed versions.

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.
- Keep public media discovery and commercial journeys inside DuniaMedia: owner, channel, presenter, programme, campaign and rate-card content must be presented on the portal rather than sending visitors to third-party sites. Use internal routes or on-page anchors for navigation and enquiries. Retain external research URLs in internal data for provenance and show non-clickable source attribution where appropriate. Do not add outbound social, broadcaster or campaign-story links to public media discovery pages.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If a frontend change doesn't show in the UI or you get a "Unable to locate file in Vite manifest" error, run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Use `search-docs` before changes that depend on Laravel ecosystem APIs, behavior, configuration, or version-specific syntax. Skip it for copy-only edits and other changes where package documentation is irrelevant. Reuse sufficient results already in context instead of searching again.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Project Rules

- This project contains committed, area-grouped rules in `.ai/rules` when that directory exists, including path-scoped framework guidelines under `.ai/rules/boost`. Before you enter plan mode or create/edit any file, you MUST first: open @.ai/rules/index.md (it maps file globs to rule files), read every rule file whose globs cover the path(s) in scope, and run `grep -rin 'keyword' .ai/rules` to catch what a path match alone misses. Do not write code until you have read and are following every matching rule. If `.ai/rules` does not exist, continue without it.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.
- Activate the `deploying-to-cloud` skill whenever deploying to Laravel Cloud, configuring Cloud environments or resources, using the Cloud CLI, or troubleshooting Cloud deployments.

=== tests rules ===

# Test Enforcement

- Add or update tests for behavior and logic changes when a test provides meaningful regression coverage.
- Pure copy, styling, and layout-only changes do not require new or updated tests.
- When test coverage applies, run the affected tests and ensure they pass.
- Test the changed behavior and its important failure modes, but do not add tests beyond them.
- Read the `testing-best-practices` skill before writing tests.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

=== livewire/core rules ===

# Livewire

- Livewire allows you to build dynamic, reactive interfaces in PHP without writing JavaScript.
- You can use Alpine.js for client-side interactions instead of JavaScript frameworks.
- Keep state server-side so the UI reflects it. Validate and authorize in actions as you would in HTTP requests.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== pest/core rules ===

# Pest

- This project uses Pest. Create tests with `php artisan make:test --pest {name}`.
- Do not include the test suite directory in `{name}`. Use `SomeFeatureTest`, not `Feature/SomeFeatureTest`.
- Read the `testing-best-practices` skill for guidance on coverage, naming, structure, dependency isolation, and review.
- Do not delete tests or test files without approval. They are part of the application.

## Running Tests

- Run the narrowest set of tests that covers the change. Pass a file path or `--filter=testName` to `php artisan test --compact`.
- Rerun a test after each change to it.
- Run `vendor/bin/pest` to call the test runner directly. It accepts the same file path and `--filter=testName` arguments.

=== aiarmada/commerce-support/laravel-dune rules ===

## Laravel Dune for Consumer Applications

These conventions apply to application-owned features that adopt Laravel Dune. Installed Commerce packages retain their own documented models, contracts, and verification workflow.

Core rule: **Make the locally obvious change the globally correct change. Unsafe shortcuts fail mechanically with errors naming the supported owner.**

### The Five Architectural Laws

1. **Conventional path requires fewer decisions:** Inject concrete feature `Actions` directly via Laravel's container. Add `Api/` interfaces only for real cross-feature or external boundaries.
2. **Forbidden dependencies fail mechanically:** `php tools/architecture-check.php` (AST) and Larastan `OwnedWriteRule` enforce boundaries and single-writer ownership in CI. Never bypass them with suppressions.
3. **Every durable value has one obvious owner:** Each `table:*` and `store:*` belongs to exactly one feature in `app/Features/<Name>/feature.json`. Only listed `Actions/` or `Persistence/` classes in that feature may mutate it.
4. **New product work adds isolated files, not branches in shared roots:** Place routes, views, migrations, and domain `Extensions/` inside the owning feature folder so `PlatformServiceProvider` discovers them automatically.
5. **Exceptions are narrow, explicit, and expiry-dated:** Architecture exceptions live exclusively in `tools/architecture.json` with exact file, exact target, reason, owner, and expiry date (never wildcards).

### Five Public Nouns & Directory Boundaries

- **Framework Core (`app/Platform/`):** Generic discovery, `FeatureModule`, `ExtensionRegistry`, strict runtime defaults. **Never imports `app/Features/` or `app/Host/`.**
- **Feature (`app/Features/<Name>/`):** Owned capability folder (`feature.json`, `FeatureServiceProvider.php`, `Actions/`, `Models/`, `EntryPoints/`, `Routes/`, optional `Api/`, `Queries/`, `Extensions/`, `Persistence/`, `Views/`). Sibling features may import only another feature's `Api/` namespace.
- **Entrypoints (`Routes/{web,api,console}.php` & `EntryPoints/`):** Thin HTTP controllers, FormRequests, jobs, console commands, Livewire/Filament adapters. Validate and authorize at the boundary, then delegate immediately to an Action.
- **Extensions (`Extensions/`):** Pluggable domain units (widgets, tools, renderers, handlers) discovered via `ExtensionRegistry` instead of central `match`/`switch` blocks.
- **Host (`app/Host/`):** External SDKs, payment/LLM/mail gateways, OS/process exec, and raw infrastructure behind pure contracts in `app/Shared/Contracts/`. **Never imports concrete features.**

### Shortest Supported Write Path

<code-snippet name="Thin Entrypoint calling a Concrete Owning Action" lang="php">
declare(strict_types=1);

namespace App\Features\Orders\EntryPoints\Http;

use App\Features\Orders\Actions\CancelOrder;
use Illuminate\Http\JsonResponse;

final readonly class CancelOrderController
{
    public function __invoke(CancelOrderRequest $request, int $orderId, CancelOrder $cancelOrder): JsonResponse
    {
        $receipt = $cancelOrder->handle(
            orderId: $orderId,
            actorId: $request->user()->id,
            reason: $request->validated('reason'),
        );

        return new JsonResponse(['order_id' => $receipt->orderId, 'status' => $receipt->status]);
    }
}
</code-snippet>

### Banned Anti-Slop Patterns in Product Code

- **No hidden Observer/model-hook mutations:** Keep write side effects in named `Actions`; observers/listeners may only delegate to an Action.
- **No cross-feature mutable Eloquent leaks:** Never return an Eloquent `Model`, `Builder`, or `Illuminate\*` runtime type from `Api/` or `app/Shared/Contracts/`. Use `final readonly` DTOs, IDs, or backed enums.
- **No explicit `$table` omissions:** Every concrete feature Eloquent model MUST declare a literal `protected $table = '...';`.
- **No imperative timing hacks:** Never call `sleep()`, `usleep()`, `time_nanosleep()`, or `Sleep::sleep()` as synchronization workarounds.
- **No `env()` outside `config/`:** Always read via `config()`.
- **No `Model::unguard()` or mass-assignment bypasses** and **no inline `@phpstan-ignore` / `phpcs:ignore` suppressions**.
- **No narrative comments (~99% ban):** Keep only legal headers, verified external vendor quirks, pure formatter ignores, public API contract docs, and RFC/issue links. Encode `DO NOT REMOVE` invariants as types, lints, or tests.

### Boost MCP & Skill Workflow

- Consult `.ai/rules/index.md` before planning or editing any file.
- Use Boost `search-docs` for version-accurate Laravel/ecosystem APIs and `database-schema` / `last-error` / `browser-logs` for evidence-backed verification.
- Record durable judgment-only rules via Boost's `record-rule` MCP tool (never edit `.ai/rules/index.md` inconsistently by hand).
- Activate on-demand skills when relevant: `laravel-dune` (feature development & bootstrap), `laravel-dune-convert` (legacy migration), `laravel-dune-verify` (runtime verification & Feature Map), `laravel-dune-correct` (turning repeated corrections into mechanical checks).
- Run affected Pest/PHPUnit tests and the application's Dune architecture checks once the scaffold is installed.

=== aiarmada/commerce-support/rules/laravel-dune rules ===

# Laravel Dune Path-Scoped Rules

These rules apply to application-owned code in apps that adopt Laravel Dune. Boost extracts each `@scoped` block into `.ai/rules/boost/` when scoped guidelines are enabled.

# Platform Core

## Strict One-Way Dependency

`app/Platform/**` MUST NEVER import or reference any class under `App\Features\*` or `App\Host\*`. Use generic contracts, PHP attributes, or manifest metadata (`feature.json`) instead.

## Zero Per-Request Disk Globs in Production

`FeatureCatalog::load()` MUST memoize in-memory per process and read from `bootstrap/cache/platform.php` when present. Any change to discovery or manifest loading must keep `php artisan platform:cache` and `php artisan optimize` compatible.

## Narrow Architecture Exceptions Only

Low-level framework mechanics (reflection, runtime strictness switches, service-provider wiring) belong here rather than in product features. Changes to `app/Platform/**` are architecture changes and require explicit review plus regression tests proving both passing and failing boundary cases.

# Feature Modules

## Owned Capability Folder & Manifest

- Every directory under `app/Features/<PascalCase>/` must contain `feature.json` (schema v1) and `FeatureServiceProvider.php` extending `App\Platform\FeatureModule`.
- Declare every owned `table:<name>` and `store:<name>` in `feature.json` with the exact local `Actions\` or `Persistence\` FQCNs permitted to mutate it. Never register an `EntryPoints\` class as a write owner.
- Multiple named actions in the same feature (e.g. `PlaceOrder`, `CancelOrder`) may write the same owned table—do not create a god-class `OrderWriter`.

## Concrete Actions by Default

- Use concrete `final readonly class` Actions resolved automatically by Laravel's container. Do not create a redundant interface or repository for single-feature use cases.
- Actions own their `DB::transaction(...)` boundaries, explicit actor/tenant authorization checks (so jobs and CLI cannot bypass HTTP FormRequests), and idempotency on retried operations.

## Models Stay Private & Explicit

- Every concrete Eloquent model under `Models/` MUST declare an explicit literal `protected $table = '...';` matching a `table:*` owned by this feature.
- Never import another feature's `Models/`, `Actions/`, `Persistence/`, or `EntryPoints/`. Cross-feature reads/writes go strictly through the owning feature's `Api/` namespace.
- Keep mutation side effects out of Eloquent Observers and `booted()` hooks; observers/listeners may only delegate to a declared named Action.

## Thin EntryPoints & Isolated Routes

- `EntryPoints/{Http,Jobs,Console,Livewire,Filament}/` only parse/validate transport input, authorize the actor, call the Action/Query, and format the response.
- Feature route files (`Routes/web.php`, `Routes/api.php`, `Routes/console.php`) MUST declare their own middleware, URI prefixes, and route name groups explicitly.
- If overriding `FeatureServiceProvider::boot()`, always call `parent::boot()`.
- For repeatable domain plugs (widgets, tools, renderers, handlers), add an isolated class under `Extensions/` and register it with `ExtensionRegistry` instead of branching in a shared root file.

# Public API & Shared Contracts

## Pure Immutable Boundary Shapes

- All classes in `app/Features/*/Api/**` and `app/Shared/Contracts/**` must be `interface`, `enum`, or `final readonly class` value objects / DTOs.
- NEVER expose Eloquent `Model`, `Builder`, `Collection`, `Request`, `Response`, or any `Illuminate\*` runtime type in public signatures or DTO properties. Use scalar/branded IDs, backed enums, `CarbonImmutable`, or immutable DTOs.

## Parse Once at the Boundary

- Validate and parse external/untrusted payloads once at the transport or Host boundary into typed DTOs. Inside features, trust typed domain values—do not duplicate defensive null/array checks.
- Public contracts may depend only on PHP built-ins, backed enums, `App\Shared\Contracts\*`, or another feature's `Api\*` types.

# Host & External Boundaries

## Keep Privileged Runtime & SDKs in the Box

- All third-party HTTP/SDK integrations (Stripe, LLM providers, mail/SMS APIs, webhooks), filesystem gateways, and OS/process execution (`Process::run`) live in `app/Host/<Integration>/`.
- Each Host integration implements a pure interface defined in `app/Shared/Contracts/` and is bound in `App\Providers\AppServiceProvider` (or a dedicated Host provider).

## Direction & Isolation

- `app/Host/**` MUST NEVER import any class under `App\Features\*`.
- Parse raw third-party responses/webhooks at the Host boundary into immutable contract DTOs or domain exceptions—never leak vendor SDK objects or raw HTTP arrays into Features.
- Features call Host behavior exclusively through the typed interface in `App\Shared\Contracts\*`, making external boundaries trivially fakeable in tests without mocking internals.

# Verification, Hygiene & Anti-Slop Governance

## Comment-Sicko Policy (~99% Comment Ban)

Only five comment types may remain in committed code:
1. Legal or license headers.
2. Verified external vendor/framework quirks we cannot reshape (proven on a live path).
3. Purely stylistic formatter ignores (`// prettier-ignore`). Never keep correctness/type suppressions (`@phpstan-ignore*`, `phpcs:ignore`).
4. Public API contract docblocks (`Api/`, `Shared/Contracts/`, PHPStan generic templates).
5. Issue or RFC links explaining an unavoidable external constraint.

Delete all narrating comments, commented-out code, workaround justifications, and over-rotated PR feedback (`// X said never do this`). Convert `DO NOT REMOVE` comments into types, lints, or tests.

## Banned Shortcuts

- No `sleep()`, `usleep()`, `time_nanosleep()`, or `Sleep::sleep()` synchronization hacks in `app/`.
- No `env()` calls outside `config/`.
- No `Model::unguard()` or disabling mass-assignment protection in `app/`.
- No service-locator `app(...)` / `resolve(...)` inside Feature Actions when constructor DI works.

## Proof Before Done ("Prove It Works")

- Always run `composer arch:check` (AST boundaries + Larastan `OwnedWriteRule` + normal PHPStan) and affected Pest/PHPUnit tests.
- Test observable behavior (HTTP status/JSON, persisted DB state read back, authorization/tenant isolation, rollback on failure, idempotency on retry)—never pin tests to private helper calls.
- Verify route/config/platform caches (`php artisan route:cache`, `config:cache`, `platform:cache`) on structural changes and clear temporary caches after.
- Use Laravel Boost MCP (`last-error`, `browser-logs`, `database-query`) to confirm zero silent runtime errors.

## Rule → Enforcement Table (Updated via `laravel-dune-correct`)

| Mistake Class | Mechanical Enforcer | Layer |
| --- | --- | --- |
| Cross-feature private import | `php tools/architecture-check.php` (`feature-private`) | L2 AST |
| `app/Platform` / `app/Host` / `app/Shared` importing feature | `php tools/architecture-check.php` (`layer-direction`) | L2 AST |
| Unregistered DB / Cache / Storage / Redis write | PHPStan `OwnedWriteRule` (`architecture.writeOwner`) | L2 Type |
| Missing literal `$table` on feature model | `php tools/architecture-check.php` (`model-table`) | L2 AST |
| Mutable Laravel / Eloquent leak in `Api/` or `Contracts/` | `php tools/architecture-check.php` (`api-shape`, `api-leak`) | L2 AST |
| `sleep()` / `env()` / `Model::unguard()` / `@phpstan-ignore` | `php tools/architecture-check.php` (`banned-footgun`, `no-suppression`) | L2 AST |
| Narrative / workaround comment slop | `php tools/architecture-check.php` (`comment-hygiene`) + `laravel-dune-correct` | L2/L4 |
| Behavioral regressions & broken routes | `verify/feature-map/` + Pest feature & arch tests + Boost MCP | L3 Verify |

</laravel-boost-guidelines>
