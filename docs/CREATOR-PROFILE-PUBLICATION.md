# Creator profiles: Dr Qistina

The creator media kit is available at `/creators/drqis` after the creator has been approved. The creator directory, global search and profile route share the same public visibility rules.

## Deploy and onboard

1. Deploy the application and run its standard migrations/frontend build.
2. Run `php artisan db:seed --class=DrqisCreatorSeeder --force` to create the profile and import the bundled original portraits. The seeder is idempotent and does not reset subsequent approval decisions or overwrite existing profile/gallery media.
3. In the administration panel, open **Creator Network → Creator Profiles** and find `drqis`. Confirm the creator's identity, publication permission and profile details. Set status to `active`, verification status to `verified`, and record the verification date.
4. Check `/creators` and `/creators/drqis` on desktop and mobile.

The photos are bundled as local WebP assets. The smaller portrait variants are used where supported. The decorative dental-care still life is cropped from the approved design mockup. Gallery and cover are editable via Filament.

No follower counts, engagement rates, views, prices, management relationship or location have been invented. Dated account metrics may be populated through the existing creator account data model. The enquiry CTA preselects the creator in the existing contact form.

Do not publish unapproved creator records or bypass verification checks.
