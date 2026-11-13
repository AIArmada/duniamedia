# Dunia Media application architecture

## Business domains

1. **Identity and parties** — `parties` is the canonical commercial actor for organizations and people. `parties.person_id` links a human party to the shared `persons` identity. Roles and relationships hang off the party, while `contact_methods`, `social_profiles` and `addresses` attach polymorphically through the `contacting` and `addressing` packages. A party can be a media owner, media sales house, client, creator and seller at the same time.
2. **Media supply** — `media_properties`, `media_assets`, `asset_surfaces` and `placements` describe channels, physical assets and bookable opportunities. `media_offerings` is the sellable catalogue unit; `rate_cards` and `rate_card_lines` hold commercial pricing.
3. **Physical locations** — `locations`, `location_zones` and `host_structures` separate sites and venues from the structures that carry media.
4. **Rights and sales mandates** — `rights_agreements`, `rights_scopes`, `rights_permissions` and `sales_mandates` record contractual control separately from ownership.
5. **Creator network** — `creator_profiles`, `creator_accounts` and `creator_delivery_templates` connect creators to social properties and sponsored deliverables.
6. **Inventory** — `inventory_pools`, `inventory_windows`, `inventory_blackouts` and `inventory_holds` model bookable capacity. Reserved totals are a cache; booking writes must lock the window inside one transaction.
7. **Selling** — `enquiries`, `quotes`, `quote_lines`, `bookings`, `booking_lines`, `inventory_allocations`, `invoices` and `payments` cover the commercial funnel. A website request creates an enquiry and a quote, never a booking.
8. **Clients, brands and campaigns** — `brands`, `advertised_items`, `projects`, `campaigns` and their joins hold advertiser demand and historical work.
9. **Creative delivery** — `files`, `creative_assets`, `campaign_flights`, `flight_placements`, `creator_deliverables`, `delivery_events`, `delivery_proofs` and `performance_metrics` cover production and reporting.
10. **History and trust** — `case_studies`, `testimonials`, `portfolio_media` and `client_references` hold published proof and display permissions.
11. **Governance** — `record_verifications`, `external_references` and `audit_events` provide polymorphic audit and verification records.

## Customer workflows

- Awareness → Home → Media Explorer or Advisor → Shortlist → Quote request → Sales review.
- Creator applicant → form → pending `creator_profiles` record → manual verification → active profile.
- Asset owner → form → prospect `media_properties` / `media_assets` record → ownership and rights review.
- AI training lead → Academy → contact → enquiry.

## Vocabulary mapping

- `media_groups` is the internal **Media Group** layer.
- `media_types` is the **Media Category / Medium** layer, linked to a media group.
- `distribution_platforms` is the **Distribution Platform** layer; `property_distribution_platforms` attaches a channel to its delivery platforms.
- `media_offerings.medium` is the **Medium**.
- `media_offerings.channel_label` is the **Channel**.
- `asset_surfaces` is the **Ad Surface**.
- `placements` is the **Ad Placement**.
- `media_offerings` is the **Media Offering**.
- `carts` instances are **Campaign Plans**; `checked_out_at` marks conversion to a quote request.

## Data rules

- Primary keys are UUIDs. Foreign-key columns use `foreignUuid` without database constraints.
- Cascades, integrity and archival are enforced in models, Actions and services.
- No soft deletes. Lifecycle state lives in `status` plus explicit transition timestamps.
- JSON columns use `database.json_column_type`, configured to `jsonb` for PostgreSQL.
- Schema changes edit the owning migration in place. There are no incremental patch migrations for the initial schema.

## Package decisions

The `../commerce/packages` suite is installed through a path repository. Owner scoping is disabled for the packages that support it because this application is single-tenant.

- `aiarmada/addressing` owns addresses. `party_addresses` and the inline address fields on `locations` are gone. Parties and locations attach polymorphic addresses through `HasAddresses`, with Malaysia reference geography seeded for country, state and city resolution.
- `aiarmada/contacting` owns contact points. `party_contacts` is gone. Parties use `HasContactMethods` and `HasSocialProfiles`, so email, phone, WhatsApp and social handles share one normalized model with verification and validity windows.
- `aiarmada/persons` owns human identity. `people` is gone. `parties.person_id` links a party to a shared `persons` record, with `person_names`, titles, credentials and affiliations available without duplicating identity in the party table.
- `spatie/laravel-medialibrary` owns every image, logo, cover, portfolio file and rate-card PDF. The custom `files` table and all `*_file_id` columns are gone. Models declare media collections and the Filament uploads use the media-library field.
- `aiarmada/authz` owns roles and permissions. `users.is_admin` is gone. The `super_admin` role gates the Filament panel, with `admin` and `editor` roles and resource-level permissions available through Spatie Permission.
- `aiarmada/signals` owns website behavioural analytics. A `Dunia Media Website` tracked property is seeded and the browser tracker is rendered on public pages. Campaign delivery metrics remain in `performance_metrics` because they are media-delivery facts, not browser sessions.
- `aiarmada/cart` owns saved campaign plans. Each cart instance is a separate plan, items are media offerings, conditions are package savings and metadata carries the plan name, objective, brief and dates. `checked_out_at` marks a plan converted into a quote request. There is no checkout or payment flow.
- `aiarmada/organizations` is not adopted. It models a tenant with ownership and membership invariants. Media owners and clients are party roles here, not tenants. Adding it would create a second organization identity beside `parties` and `organizations`.
- `aiarmada/customers`, `orders`, `pricing`, `inventory` and `growth` are not adopted. `growth` is an A/B-testing engine, not analytics, and there are no experiments to run yet. `customers`, `orders`, `pricing` and `inventory` duplicate the media-specific selling chain (`media_offerings`, `placements`, `rate_cards`, `enquiries`, `quotes`, `bookings`, `inventory_windows`). The media booking flow needs date windows, impressions and campaign flights, not stock or generic order state machines.
- `aiarmada/commerce-support` is installed as the shared foundation and provides the package conventions: UUIDs, `foreignUuid`, `timestampTz`, `status`, configurable JSON columns and owner-scope infrastructure.

## Deployment readiness

NOT PRODUCTION READY: complete framework smoke tests, media owner verification, legal policy sign-off, privacy review, backup testing, rate limiting, captcha and admin permission hardening before public release.
