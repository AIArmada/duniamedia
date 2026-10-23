# Dunia Media application architecture

## Business domains

1. **Media inventory** — `media_listings` with category, region, pricing display policy and availability verification state. Admin can publish/unpublish.
2. **Creator inventory** — `creators` directory (verified status), `creator_applications` intake, niche/platform filters.
3. **Commercial funnel** — `leads`, `quotes`, `quote_items`; proposal requests stay `requested` and `private`; sales team confirms rates with owners.
4. **Media asset supply** — `asset_submissions` for new location/property opportunities, reviewed manually before listing.
5. **Brand/content/academy** — `services`, `projects`, `articles`, `trainings`.

## Customer workflows
- Awareness → Home → Media Explorer or Advisor → Shortlist → Quote Request → Sales review.
- Creator applicant → Form → Pending → Manual verification → Profile.
- Asset owner → Form → Ownership/rights due diligence → Media listing.
- AI training lead → Academy → Contact → Sales review.

## Proposed next implementation wave
- Supplier/sales agreements, inventory calendar and verified slot sync.
- Admin price permissions, draft/sent quotation PDFs, taxes, revision history, e-signature.
- CRM lifecycle actions, scheduled follow-up, email templates, notifications and role-based audit trails.
- Creator OAuth analytics permissions and first-party metric ingestion, contract/payments, audience overlap evaluation.
- Analytics measurement design: UTM, QR codes, tracked links and offline conversion import. Consent and retention policies.
- Genuine AI adviser implementation with grounding in approved listings, human review and non-hallucinated pricing.
- Tests, accessibility audit, performance budgets, security review and deployment observability.

## Deployment readiness
NOT PRODUCTION READY: complete Composer install, framework smoke tests, database migrations, supplier verification, legal policy sign-off, privacy review, backup testing, rate limiting, captcha and admin permission hardening before public release.
