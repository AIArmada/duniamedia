# Rate-card extraction audit — every page checklist

Generated 2026-11-26 from the source PDFs in `storage/app/private/ratecards/` and the catalogue database.

## Result

- **41 current PDFs / 756 pages audited**
- **247 table pages detected**
- **1,238 price values detected on page text**
- **0 unmatched price values** on current decks
- **4,667 embedded images detected**; 361 meaningful photography assets are published: 27 official/CC-licensed and 334 source-deck photographs (logos, icons, masks and tiny decorations excluded by design)
- Bernama (24 pages) and TV AlHijrah (5 pages) are scanned/image-only decks. Their pages were rendered and checked visually against the seeded package tables; Bernama has 91 extracted lines and TV AlHijrah has 20.
- The two superseded KLIA 2024 decks are archived reference only and are excluded from the 756-page current-deck total. They are listed separately at the end with their unmatched values.

## Method

1. Every page of every current PDF was opened and its extractable text, tables, price-like values and embedded images counted.
2. Each detected price value was normalised and matched against the seeded `rate_card_entries` for the same deck, page and amount.
3. A page is `OK` only when it has no unmatched price value. Scanned pages are marked `Vision checked` after visual comparison.
4. The public site never exposes the PDF; this audit is the internal provenance ledger.

## Current decks

| PDF | Pages | Table pages | Text chars | Price values | Missing | Images | Status |
| --- | ---: | ---: | ---: | ---: | ---: | ---: | --- |
| `988-fm-2026-radio-digital.pdf` | 4 | 3 | 8,577 | 19 | 0 | 0 | OK |
| `astro-upfront-2027.pdf` | 66 | 38 | 97,246 | 140 | 0 | 250 | OK |
| `astro-video360-digital-2026.pdf` | 21 | 1 | 2,083 | 0 | 0 | 70 | OK |
| `bernama-media-kit-2026.pdf` | 24 | 0 | 0 | 0 | 0 | 24 | Vision checked |
| `bfm-2026-business-radio.pdf` | 28 | 2 | 11,897 | 29 | 0 | 131 | OK |
| `billboard-sarawak-smg-2026.pdf` | 19 | 7 | 7,568 | 47 | 0 | 37 | OK |
| `bus-wrap-klang-valley-2025.pdf` | 17 | 7 | 33,678 | 0 | 0 | 66 | OK |
| `cubig-klcc-junction-2026-promo.pdf` | 2 | 0 | 1,783 | 6 | 0 | 43 | OK |
| `erl-in-train-network-2026.pdf` | 9 | 2 | 2,721 | 1 | 0 | 53 | OK |
| `erl-station-coverage-2026.pdf` | 10 | 2 | 2,525 | 1 | 0 | 71 | OK |
| `fmt-2026-digital-news.pdf` | 8 | 2 | 10,053 | 11 | 0 | 14 | OK |
| `hot-fm-zoomerz-on-ground.pdf` | 11 | 3 | 4,157 | 24 | 0 | 42 | OK |
| `ikim-fm-ramadan-2027.pdf` | 11 | 1 | 5,829 | 27 | 0 | 10 | OK |
| `karnival-jom-heboh-2026-venue.pdf` | 8 | 0 | 2,760 | 0 | 0 | 144 | OK |
| `kl-sentral-station-sponsorship-2024.pdf` | 19 | 3 | 8,724 | 0 | 0 | 153 | OK |
| `klcc-snorkels-2024-big-tree.pdf` | 14 | 3 | 5,773 | 0 | 0 | 173 | OK |
| `klia-t1-static-2026.pdf` | 56 | 39 | 24,374 | 90 | 0 | 171 | OK |
| `klia-t1-t2-digital-2026.pdf` | 62 | 15 | 17,069 | 17 | 0 | 142 | OK |
| `klia-t2-static-2026.pdf` | 31 | 19 | 13,536 | 57 | 0 | 97 | OK |
| `konsert-kita-suara-anti-buli-2026.pdf` | 9 | 0 | 3,710 | 1 | 0 | 34 | OK |
| `ktm-train-kl-sentral-ads-2026.pdf` | 5 | 1 | 2,477 | 18 | 0 | 18 | OK |
| `lamppost-digital-bunting-kl-2026.pdf` | 6 | 0 | 2,917 | 0 | 0 | 26 | OK |
| `malaysiakini-kinitv-event-coverage-2026-2027.pdf` | 8 | 0 | 3,765 | 9 | 0 | 28 | OK |
| `malaysiakini-media-kit-2024.pdf` | 45 | 8 | 21,092 | 39 | 0 | 212 | OK |
| `media-prima-audio-trade-show-2025.pdf` | 1 | 1 | 2,824 | 3 | 0 | 5 | OK |
| `mobile-truck-activation-life-events.pdf` | 6 | 0 | 1,707 | 4 | 0 | 11 | OK |
| `omnia-content-terhangat-2026.pdf` | 24 | 12 | 41,117 | 125 | 0 | 202 | OK |
| `podcast-utama-buletin-tv3.pdf` | 18 | 0 | 8,423 | 4 | 0 | 315 | OK |
| `ra-media-relations-live-tv-talkshow-2026-2027.pdf` | 1 | 1 | 1,501 | 20 | 0 | 1 | OK |
| `ra-media-relations-news-slot-2026-2027.pdf` | 1 | 1 | 853 | 14 | 0 | 1 | OK |
| `ra-media-website-media-solutions-2026.pdf` | 28 | 20 | 56,187 | 120 | 0 | 2 | OK |
| `rtm-tv-radio-2025.pdf` | 50 | 35 | 21,719 | 34 | 0 | 43 | OK |
| `sinar-harian-20th-anniversary-2026.pdf` | 3 | 3 | 6,775 | 12 | 0 | 172 | OK |
| `sinar-karangkraf-ratecard-2025-2026.pdf` | 38 | 12 | 51,796 | 133 | 0 | 1,288 | OK |
| `suria-fm-2026-radio-digital.pdf` | 6 | 0 | 6,108 | 7 | 0 | 13 | OK |
| `target-media-elevator-2026.pdf` | 1 | 1 | 1,753 | 7 | 0 | 1 | OK |
| `tmr-super-deal-2026-business-content.pdf` | 8 | 0 | 6,502 | 23 | 0 | 69 | OK |
| `tvalhijrah-bincang-niaga-2024.pdf` | 5 | 0 | 0 | 0 | 0 | 5 | Vision checked |
| `tvs-profile-2026.pdf` | 21 | 4 | 13,987 | 92 | 0 | 105 | OK |
| `tvs-sponsorship-spot-buy-2026.pdf` | 23 | 0 | 8,608 | 56 | 0 | 188 | OK |
| `wowshop-airtime-2025.pdf` | 29 | 1 | 7,814 | 48 | 0 | 237 | OK |

## Per-page checklist

### `988-fm-2026-radio-digital.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 2,179 | 2 | 0 | 0 | 0 | OK |
| 2 | 2,787 | 0 | 19 | 0 | 0 | OK |
| 3 | 2,372 | 2 | 0 | 0 | 0 | OK |
| 4 | 1,239 | 1 | 0 | 0 | 0 | OK |

### `astro-upfront-2027.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 14 | 0 | 0 | 0 | 6 | OK |
| 2 | 220 | 0 | 0 | 0 | 9 | OK |
| 3 | 445 | 0 | 0 | 0 | 7 | OK |
| 4 | 1,240 | 1 | 0 | 0 | 1 | OK |
| 5 | 1,291 | 1 | 0 | 0 | 1 | OK |
| 6 | 648 | 1 | 0 | 0 | 1 | OK |
| 7 | 18 | 0 | 0 | 0 | 6 | OK |
| 8 | 35 | 0 | 0 | 0 | 5 | OK |
| 9 | 3,713 | 1 | 8 | 0 | 2 | OK |
| 10 | 50 | 0 | 0 | 0 | 6 | OK |
| 11 | 51 | 0 | 0 | 0 | 3 | OK |
| 12 | 55 | 0 | 0 | 0 | 3 | OK |
| 13 | 58 | 0 | 0 | 0 | 3 | OK |
| 14 | 52 | 0 | 0 | 0 | 4 | OK |
| 15 | 2,571 | 0 | 0 | 0 | 1 | OK |
| 16 | 47 | 0 | 0 | 0 | 5 | OK |
| 17 | 1,659 | 1 | 5 | 0 | 6 | OK |
| 18 | 836 | 1 | 4 | 0 | 7 | OK |
| 19 | 1,600 | 1 | 5 | 0 | 6 | OK |
| 20 | 806 | 1 | 4 | 0 | 7 | OK |
| 21 | 20 | 0 | 0 | 0 | 6 | OK |
| 22 | 2,762 | 1 | 2 | 0 | 1 | OK |
| 23 | 2,710 | 1 | 2 | 0 | 1 | OK |
| 24 | 2,711 | 1 | 2 | 0 | 1 | OK |
| 25 | 4,547 | 1 | 33 | 0 | 1 | OK |
| 26 | 3,605 | 1 | 1 | 0 | 29 | OK |
| 27 | 3,648 | 1 | 3 | 0 | 3 | OK |
| 28 | 4,204 | 1 | 3 | 0 | 3 | OK |
| 29 | 3,742 | 1 | 3 | 0 | 3 | OK |
| 30 | 3,761 | 1 | 3 | 0 | 3 | OK |
| 31 | 15 | 0 | 0 | 0 | 6 | OK |
| 32 | 46 | 0 | 0 | 0 | 6 | OK |
| 33 | 1,368 | 1 | 3 | 0 | 1 | OK |
| 34 | 1,106 | 0 | 3 | 0 | 1 | OK |
| 35 | 2,560 | 0 | 0 | 0 | 1 | OK |
| 36 | 2,540 | 1 | 2 | 0 | 2 | OK |
| 37 | 2,658 | 1 | 2 | 0 | 2 | OK |
| 38 | 32 | 0 | 0 | 0 | 5 | OK |
| 39 | 2,740 | 1 | 2 | 0 | 2 | OK |
| 40 | 2,402 | 1 | 5 | 0 | 2 | OK |
| 41 | 1,622 | 1 | 2 | 0 | 2 | OK |
| 42 | 2,229 | 2 | 4 | 0 | 2 | OK |
| 43 | 35 | 0 | 0 | 0 | 6 | OK |
| 44 | 1,089 | 1 | 5 | 0 | 5 | OK |
| 45 | 643 | 1 | 2 | 0 | 5 | OK |
| 46 | 2,615 | 1 | 2 | 0 | 4 | OK |
| 47 | 44 | 0 | 0 | 0 | 5 | OK |
| 48 | 2,440 | 1 | 2 | 0 | 1 | OK |
| 49 | 2,707 | 1 | 2 | 0 | 1 | OK |
| 50 | 2,331 | 1 | 4 | 0 | 1 | OK |
| 51 | 2,634 | 1 | 4 | 0 | 1 | OK |
| 52 | 30 | 0 | 0 | 0 | 6 | OK |
| 53 | 63 | 0 | 0 | 0 | 6 | OK |
| 54 | 644 | 1 | 4 | 0 | 1 | OK |
| 55 | 639 | 2 | 4 | 0 | 1 | OK |
| 56 | 965 | 2 | 2 | 0 | 1 | OK |
| 57 | 53 | 0 | 0 | 0 | 5 | OK |
| 58 | 1,539 | 1 | 1 | 0 | 1 | OK |
| 59 | 1,647 | 1 | 1 | 0 | 1 | OK |
| 60 | 22 | 0 | 0 | 0 | 8 | OK |
| 61 | 2,796 | 1 | 3 | 0 | 2 | OK |
| 62 | 27 | 0 | 0 | 0 | 6 | OK |
| 63 | 54 | 0 | 0 | 0 | 6 | OK |
| 64 | 1,994 | 1 | 1 | 0 | 1 | OK |
| 65 | 5,617 | 0 | 2 | 0 | 1 | OK |
| 66 | 181 | 0 | 0 | 0 | 3 | OK |

### `astro-video360-digital-2026.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 38 | 0 | 0 | 0 | 6 | OK |
| 2 | 130 | 0 | 0 | 0 | 6 | OK |
| 3 | 462 | 0 | 0 | 0 | 2 | OK |
| 4 | 119 | 0 | 0 | 0 | 4 | OK |
| 5 | 209 | 1 | 0 | 0 | 2 | OK |
| 6 | 366 | 0 | 0 | 0 | 2 | OK |
| 7 | 339 | 0 | 0 | 0 | 2 | OK |
| 8 | 107 | 0 | 0 | 0 | 4 | OK |
| 9 | 124 | 0 | 0 | 0 | 7 | OK |
| 10 | 125 | 0 | 0 | 0 | 7 | OK |
| 11 | 64 | 0 | 0 | 0 | 7 | OK |
| 12 | 0 | 0 | 0 | 0 | 3 | OK |
| 13 | 0 | 0 | 0 | 0 | 3 | OK |
| 14 | 0 | 0 | 0 | 0 | 3 | OK |
| 15 | 0 | 0 | 0 | 0 | 3 | OK |
| 16 | 0 | 0 | 0 | 0 | 1 | OK |
| 17 | 0 | 0 | 0 | 0 | 4 | OK |
| 18 | 0 | 0 | 0 | 0 | 1 | OK |
| 19 | 0 | 0 | 0 | 0 | 1 | OK |
| 20 | 0 | 0 | 0 | 0 | 1 | OK |
| 21 | 0 | 0 | 0 | 0 | 1 | OK |

### `bernama-media-kit-2026.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 0 | 0 | 0 | 0 | 1 | Vision checked |
| 2 | 0 | 0 | 0 | 0 | 1 | Vision checked |
| 3 | 0 | 0 | 0 | 0 | 1 | Vision checked |
| 4 | 0 | 0 | 0 | 0 | 1 | Vision checked |
| 5 | 0 | 0 | 0 | 0 | 1 | Vision checked |
| 6 | 0 | 0 | 0 | 0 | 1 | Vision checked |
| 7 | 0 | 0 | 0 | 0 | 1 | Vision checked |
| 8 | 0 | 0 | 0 | 0 | 1 | Vision checked |
| 9 | 0 | 0 | 0 | 0 | 1 | Vision checked |
| 10 | 0 | 0 | 0 | 0 | 1 | Vision checked |
| 11 | 0 | 0 | 0 | 0 | 1 | Vision checked |
| 12 | 0 | 0 | 0 | 0 | 1 | Vision checked |
| 13 | 0 | 0 | 0 | 0 | 1 | Vision checked |
| 14 | 0 | 0 | 0 | 0 | 1 | Vision checked |
| 15 | 0 | 0 | 0 | 0 | 1 | Vision checked |
| 16 | 0 | 0 | 0 | 0 | 1 | Vision checked |
| 17 | 0 | 0 | 0 | 0 | 1 | Vision checked |
| 18 | 0 | 0 | 0 | 0 | 1 | Vision checked |
| 19 | 0 | 0 | 0 | 0 | 1 | Vision checked |
| 20 | 0 | 0 | 0 | 0 | 1 | Vision checked |
| 21 | 0 | 0 | 0 | 0 | 1 | Vision checked |
| 22 | 0 | 0 | 0 | 0 | 1 | Vision checked |
| 23 | 0 | 0 | 0 | 0 | 1 | Vision checked |
| 24 | 0 | 0 | 0 | 0 | 1 | Vision checked |

### `bfm-2026-business-radio.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 17 | 0 | 0 | 0 | 3 | OK |
| 2 | 277 | 0 | 0 | 0 | 1 | OK |
| 3 | 168 | 0 | 0 | 0 | 3 | OK |
| 4 | 859 | 0 | 0 | 0 | 1 | OK |
| 5 | 599 | 0 | 6 | 0 | 1 | OK |
| 6 | 542 | 1 | 0 | 0 | 5 | OK |
| 7 | 479 | 0 | 6 | 0 | 1 | OK |
| 8 | 506 | 1 | 6 | 0 | 5 | OK |
| 9 | 1,190 | 0 | 2 | 0 | 1 | OK |
| 10 | 806 | 0 | 0 | 0 | 1 | OK |
| 11 | 103 | 0 | 0 | 0 | 4 | OK |
| 12 | 73 | 0 | 0 | 0 | 3 | OK |
| 13 | 706 | 0 | 0 | 0 | 2 | OK |
| 14 | 718 | 0 | 2 | 0 | 4 | OK |
| 15 | 377 | 0 | 0 | 0 | 16 | OK |
| 16 | 410 | 0 | 0 | 0 | 12 | OK |
| 17 | 279 | 0 | 0 | 0 | 8 | OK |
| 18 | 103 | 0 | 0 | 0 | 4 | OK |
| 19 | 7 | 0 | 0 | 0 | 10 | OK |
| 20 | 901 | 0 | 1 | 0 | 5 | OK |
| 21 | 103 | 0 | 0 | 0 | 4 | OK |
| 22 | 7 | 0 | 0 | 0 | 4 | OK |
| 23 | 402 | 0 | 0 | 0 | 14 | OK |
| 24 | 642 | 0 | 4 | 0 | 2 | OK |
| 25 | 511 | 0 | 2 | 0 | 3 | OK |
| 26 | 805 | 0 | 0 | 0 | 1 | OK |
| 27 | 103 | 0 | 0 | 0 | 4 | OK |
| 28 | 204 | 0 | 0 | 0 | 9 | OK |

### `billboard-sarawak-smg-2026.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 48 | 0 | 0 | 0 | 2 | OK |
| 2 | 60 | 0 | 0 | 0 | 2 | OK |
| 3 | 680 | 1 | 0 | 0 | 2 | OK |
| 4 | 608 | 1 | 7 | 0 | 2 | OK |
| 5 | 188 | 0 | 0 | 0 | 4 | OK |
| 6 | 62 | 0 | 0 | 0 | 2 | OK |
| 7 | 673 | 1 | 0 | 0 | 2 | OK |
| 8 | 608 | 1 | 7 | 0 | 4 | OK |
| 9 | 230 | 0 | 0 | 0 | 2 | OK |
| 10 | 50 | 0 | 0 | 0 | 2 | OK |
| 11 | 670 | 1 | 0 | 0 | 2 | OK |
| 12 | 603 | 1 | 7 | 0 | 2 | OK |
| 13 | 210 | 0 | 0 | 0 | 2 | OK |
| 14 | 76 | 0 | 0 | 0 | 2 | OK |
| 15 | 734 | 0 | 0 | 0 | 1 | OK |
| 16 | 49 | 0 | 0 | 0 | 2 | OK |
| 17 | 837 | 1 | 24 | 0 | 1 | OK |
| 18 | 1,172 | 0 | 2 | 0 | 1 | OK |
| 19 | 10 | 0 | 0 | 0 | 0 | OK |

### `bus-wrap-klang-valley-2025.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 106 | 0 | 0 | 0 | 4 | OK |
| 2 | 281 | 0 | 0 | 0 | 4 | OK |
| 3 | 460 | 0 | 0 | 0 | 16 | OK |
| 4 | 411 | 0 | 0 | 0 | 4 | OK |
| 5 | 192 | 0 | 0 | 0 | 4 | OK |
| 6 | 218 | 0 | 0 | 0 | 4 | OK |
| 7 | 86 | 0 | 0 | 0 | 4 | OK |
| 8 | 7,390 | 1 | 0 | 0 | 0 | OK |
| 9 | 6,715 | 1 | 0 | 0 | 0 | OK |
| 10 | 4,516 | 1 | 0 | 0 | 0 | OK |
| 11 | 5,564 | 2 | 0 | 0 | 0 | OK |
| 12 | 5,061 | 1 | 0 | 0 | 0 | OK |
| 13 | 1,213 | 0 | 0 | 0 | 0 | OK |
| 14 | 480 | 1 | 0 | 0 | 6 | OK |
| 15 | 523 | 1 | 0 | 0 | 6 | OK |
| 16 | 2 | 0 | 0 | 0 | 12 | OK |
| 17 | 460 | 0 | 0 | 0 | 2 | OK |

### `cubig-klcc-junction-2026-promo.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 1,341 | 0 | 6 | 0 | 19 | OK |
| 2 | 442 | 0 | 0 | 0 | 24 | OK |

### `erl-in-train-network-2026.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 90 | 0 | 0 | 0 | 4 | OK |
| 2 | 250 | 0 | 0 | 0 | 12 | OK |
| 3 | 454 | 0 | 0 | 0 | 17 | OK |
| 4 | 528 | 0 | 0 | 0 | 2 | OK |
| 5 | 72 | 0 | 0 | 0 | 5 | OK |
| 6 | 466 | 1 | 1 | 0 | 5 | OK |
| 7 | 51 | 0 | 0 | 0 | 5 | OK |
| 8 | 474 | 1 | 0 | 0 | 1 | OK |
| 9 | 336 | 0 | 0 | 0 | 2 | OK |

### `erl-station-coverage-2026.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 80 | 0 | 0 | 0 | 5 | OK |
| 2 | 229 | 0 | 0 | 0 | 13 | OK |
| 3 | 443 | 0 | 0 | 0 | 18 | OK |
| 4 | 498 | 0 | 0 | 0 | 3 | OK |
| 5 | 504 | 2 | 1 | 0 | 5 | OK |
| 6 | 0 | 0 | 0 | 0 | 7 | OK |
| 7 | 0 | 0 | 0 | 0 | 8 | OK |
| 8 | 2 | 0 | 0 | 0 | 5 | OK |
| 9 | 474 | 1 | 0 | 0 | 3 | OK |
| 10 | 295 | 0 | 0 | 0 | 4 | OK |

### `fmt-2026-digital-news.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 418 | 0 | 0 | 0 | 1 | OK |
| 2 | 1,974 | 0 | 0 | 0 | 1 | OK |
| 3 | 1,602 | 0 | 0 | 0 | 2 | OK |
| 4 | 1,672 | 1 | 0 | 0 | 2 | OK |
| 5 | 1,336 | 0 | 0 | 0 | 2 | OK |
| 6 | 835 | 0 | 0 | 0 | 2 | OK |
| 7 | 1,590 | 1 | 2 | 0 | 2 | OK |
| 8 | 626 | 0 | 9 | 0 | 2 | OK |

### `hot-fm-zoomerz-on-ground.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 63 | 0 | 0 | 0 | 4 | OK |
| 2 | 808 | 0 | 0 | 0 | 5 | OK |
| 3 | 1,126 | 0 | 2 | 0 | 2 | OK |
| 4 | 487 | 1 | 9 | 0 | 2 | OK |
| 5 | 928 | 1 | 9 | 0 | 2 | OK |
| 6 | 23 | 0 | 0 | 0 | 9 | OK |
| 7 | 72 | 0 | 0 | 0 | 3 | OK |
| 8 | 226 | 0 | 0 | 0 | 5 | OK |
| 9 | 89 | 0 | 0 | 0 | 4 | OK |
| 10 | 324 | 1 | 4 | 0 | 2 | OK |
| 11 | 11 | 0 | 0 | 0 | 4 | OK |

### `ikim-fm-ramadan-2027.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 113 | 0 | 0 | 0 | 4 | OK |
| 2 | 333 | 0 | 4 | 0 | 0 | OK |
| 3 | 407 | 0 | 4 | 0 | 0 | OK |
| 4 | 313 | 0 | 2 | 0 | 0 | OK |
| 5 | 223 | 0 | 1 | 0 | 0 | OK |
| 6 | 493 | 0 | 2 | 0 | 0 | OK |
| 7 | 1,291 | 0 | 4 | 0 | 0 | OK |
| 8 | 855 | 0 | 3 | 0 | 0 | OK |
| 9 | 1,194 | 1 | 4 | 0 | 0 | OK |
| 10 | 347 | 0 | 1 | 0 | 5 | OK |
| 11 | 260 | 0 | 2 | 0 | 1 | OK |

### `karnival-jom-heboh-2026-venue.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 24 | 0 | 0 | 0 | 9 | OK |
| 2 | 770 | 0 | 0 | 0 | 11 | OK |
| 3 | 1,027 | 0 | 0 | 0 | 21 | OK |
| 4 | 320 | 0 | 0 | 0 | 27 | OK |
| 5 | 82 | 0 | 0 | 0 | 12 | OK |
| 6 | 51 | 0 | 0 | 0 | 19 | OK |
| 7 | 82 | 0 | 0 | 0 | 12 | OK |
| 8 | 404 | 0 | 0 | 0 | 33 | OK |

### `kl-sentral-station-sponsorship-2024.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 32 | 0 | 0 | 0 | 13 | OK |
| 2 | 805 | 0 | 0 | 0 | 14 | OK |
| 3 | 46 | 0 | 0 | 0 | 7 | OK |
| 4 | 978 | 0 | 0 | 0 | 16 | OK |
| 5 | 866 | 0 | 0 | 0 | 16 | OK |
| 6 | 301 | 0 | 0 | 0 | 22 | OK |
| 7 | 31 | 0 | 0 | 0 | 4 | OK |
| 8 | 914 | 1 | 0 | 0 | 12 | OK |
| 9 | 194 | 0 | 0 | 0 | 6 | OK |
| 10 | 194 | 0 | 0 | 0 | 6 | OK |
| 11 | 148 | 0 | 0 | 0 | 6 | OK |
| 12 | 72 | 0 | 0 | 0 | 3 | OK |
| 13 | 245 | 0 | 0 | 0 | 7 | OK |
| 14 | 127 | 0 | 0 | 0 | 5 | OK |
| 15 | 1,260 | 1 | 0 | 0 | 2 | OK |
| 16 | 670 | 1 | 0 | 0 | 2 | OK |
| 17 | 16 | 0 | 0 | 0 | 4 | OK |
| 18 | 1,725 | 0 | 0 | 0 | 4 | OK |
| 19 | 100 | 0 | 0 | 0 | 4 | OK |

### `klcc-snorkels-2024-big-tree.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 18 | 0 | 0 | 0 | 9 | OK |
| 2 | 362 | 0 | 0 | 0 | 5 | OK |
| 3 | 387 | 0 | 0 | 0 | 17 | OK |
| 4 | 19 | 0 | 0 | 0 | 18 | OK |
| 5 | 672 | 0 | 0 | 0 | 32 | OK |
| 6 | 208 | 0 | 0 | 0 | 19 | OK |
| 7 | 41 | 0 | 0 | 0 | 7 | OK |
| 8 | 476 | 1 | 0 | 0 | 12 | OK |
| 9 | 466 | 1 | 0 | 0 | 10 | OK |
| 10 | 464 | 1 | 0 | 0 | 12 | OK |
| 11 | 9 | 0 | 0 | 0 | 4 | OK |
| 12 | 725 | 0 | 0 | 0 | 20 | OK |
| 13 | 1,726 | 0 | 0 | 0 | 6 | OK |
| 14 | 200 | 0 | 0 | 0 | 2 | OK |

### `klia-t1-static-2026.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 55 | 0 | 0 | 0 | 3 | OK |
| 2 | 29 | 0 | 0 | 0 | 3 | OK |
| 3 | 606 | 1 | 3 | 0 | 3 | OK |
| 4 | 566 | 1 | 3 | 0 | 3 | OK |
| 5 | 59 | 0 | 0 | 0 | 3 | OK |
| 6 | 564 | 1 | 3 | 0 | 3 | OK |
| 7 | 63 | 0 | 0 | 0 | 3 | OK |
| 8 | 603 | 1 | 3 | 0 | 3 | OK |
| 9 | 62 | 0 | 0 | 0 | 3 | OK |
| 10 | 542 | 1 | 2 | 0 | 3 | OK |
| 11 | 545 | 1 | 3 | 0 | 3 | OK |
| 12 | 535 | 1 | 2 | 0 | 3 | OK |
| 13 | 60 | 0 | 0 | 0 | 3 | OK |
| 14 | 613 | 1 | 2 | 0 | 4 | OK |
| 15 | 48 | 0 | 0 | 0 | 3 | OK |
| 16 | 544 | 1 | 2 | 0 | 3 | OK |
| 17 | 469 | 1 | 3 | 0 | 3 | OK |
| 18 | 469 | 1 | 3 | 0 | 3 | OK |
| 19 | 566 | 1 | 2 | 0 | 3 | OK |
| 20 | 503 | 1 | 3 | 0 | 3 | OK |
| 21 | 52 | 0 | 0 | 0 | 4 | OK |
| 22 | 583 | 1 | 2 | 0 | 3 | OK |
| 23 | 545 | 1 | 3 | 0 | 3 | OK |
| 24 | 463 | 1 | 3 | 0 | 3 | OK |
| 25 | 537 | 1 | 3 | 0 | 3 | OK |
| 26 | 539 | 1 | 3 | 0 | 3 | OK |
| 27 | 447 | 1 | 2 | 0 | 3 | OK |
| 28 | 493 | 1 | 2 | 0 | 3 | OK |
| 29 | 453 | 1 | 2 | 0 | 3 | OK |
| 30 | 65 | 0 | 0 | 0 | 3 | OK |
| 31 | 496 | 1 | 3 | 0 | 3 | OK |
| 32 | 624 | 1 | 2 | 0 | 3 | OK |
| 33 | 70 | 0 | 0 | 0 | 3 | OK |
| 34 | 494 | 1 | 3 | 0 | 3 | OK |
| 35 | 587 | 1 | 2 | 0 | 3 | OK |
| 36 | 435 | 1 | 2 | 0 | 3 | OK |
| 37 | 546 | 1 | 2 | 0 | 3 | OK |
| 38 | 83 | 0 | 0 | 0 | 3 | OK |
| 39 | 551 | 1 | 3 | 0 | 3 | OK |
| 40 | 697 | 1 | 2 | 0 | 3 | OK |
| 41 | 68 | 0 | 0 | 0 | 4 | OK |
| 42 | 899 | 1 | 2 | 0 | 3 | OK |
| 43 | 471 | 1 | 1 | 0 | 3 | OK |
| 44 | 600 | 1 | 2 | 0 | 3 | OK |
| 45 | 568 | 1 | 2 | 0 | 4 | OK |
| 46 | 558 | 1 | 2 | 0 | 3 | OK |
| 47 | 109 | 0 | 0 | 0 | 3 | OK |
| 48 | 656 | 1 | 3 | 0 | 3 | OK |
| 49 | 625 | 1 | 1 | 0 | 3 | OK |
| 50 | 606 | 1 | 1 | 0 | 3 | OK |
| 51 | 682 | 1 | 1 | 0 | 3 | OK |
| 52 | 133 | 0 | 0 | 0 | 4 | OK |
| 53 | 595 | 1 | 2 | 0 | 3 | OK |
| 54 | 31 | 0 | 0 | 0 | 3 | OK |
| 55 | 1,176 | 0 | 0 | 0 | 2 | OK |
| 56 | 336 | 0 | 0 | 0 | 2 | OK |

### `klia-t1-t2-digital-2026.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 54 | 0 | 0 | 0 | 3 | OK |
| 2 | 32 | 0 | 0 | 0 | 3 | OK |
| 3 | 673 | 0 | 0 | 0 | 3 | OK |
| 4 | 621 | 1 | 1 | 0 | 4 | OK |
| 5 | 69 | 0 | 0 | 0 | 3 | OK |
| 6 | 689 | 1 | 1 | 0 | 3 | OK |
| 7 | 63 | 0 | 0 | 0 | 3 | OK |
| 8 | 615 | 1 | 1 | 0 | 3 | OK |
| 9 | 94 | 0 | 0 | 0 | 1 | OK |
| 10 | 39 | 0 | 0 | 0 | 2 | OK |
| 11 | 125 | 0 | 0 | 0 | 1 | OK |
| 12 | 664 | 1 | 1 | 0 | 3 | OK |
| 13 | 137 | 0 | 0 | 0 | 1 | OK |
| 14 | 96 | 0 | 0 | 0 | 3 | OK |
| 15 | 125 | 0 | 0 | 0 | 2 | OK |
| 16 | 588 | 1 | 1 | 0 | 3 | OK |
| 17 | 59 | 0 | 0 | 0 | 1 | OK |
| 18 | 125 | 0 | 0 | 0 | 1 | OK |
| 19 | 80 | 0 | 0 | 0 | 1 | OK |
| 20 | 662 | 1 | 1 | 0 | 3 | OK |
| 21 | 95 | 0 | 0 | 0 | 1 | OK |
| 22 | 136 | 0 | 0 | 0 | 2 | OK |
| 23 | 125 | 0 | 0 | 0 | 1 | OK |
| 24 | 649 | 1 | 1 | 0 | 3 | OK |
| 25 | 59 | 0 | 0 | 0 | 6 | OK |
| 26 | 113 | 0 | 0 | 0 | 1 | OK |
| 27 | 122 | 0 | 0 | 0 | 1 | OK |
| 28 | 125 | 0 | 0 | 0 | 1 | OK |
| 29 | 535 | 1 | 1 | 0 | 3 | OK |
| 30 | 60 | 0 | 0 | 0 | 1 | OK |
| 31 | 125 | 0 | 0 | 0 | 1 | OK |
| 32 | 899 | 1 | 2 | 0 | 3 | OK |
| 33 | 653 | 1 | 1 | 0 | 3 | OK |
| 34 | 48 | 0 | 0 | 0 | 5 | OK |
| 35 | 62 | 0 | 0 | 0 | 1 | OK |
| 36 | 648 | 1 | 1 | 0 | 3 | OK |
| 37 | 55 | 0 | 0 | 0 | 3 | OK |
| 38 | 125 | 0 | 0 | 0 | 1 | OK |
| 39 | 56 | 0 | 0 | 0 | 1 | OK |
| 40 | 610 | 1 | 1 | 0 | 3 | OK |
| 41 | 61 | 0 | 0 | 0 | 2 | OK |
| 42 | 125 | 0 | 0 | 0 | 1 | OK |
| 43 | 92 | 0 | 0 | 0 | 1 | OK |
| 44 | 32 | 0 | 0 | 0 | 3 | OK |
| 45 | 644 | 0 | 0 | 0 | 3 | OK |
| 46 | 609 | 1 | 1 | 0 | 3 | OK |
| 47 | 129 | 0 | 0 | 0 | 2 | OK |
| 48 | 92 | 0 | 0 | 0 | 1 | OK |
| 49 | 125 | 0 | 0 | 0 | 1 | OK |
| 50 | 561 | 1 | 1 | 0 | 3 | OK |
| 51 | 70 | 0 | 0 | 0 | 1 | OK |
| 52 | 70 | 0 | 0 | 0 | 1 | OK |
| 53 | 62 | 0 | 0 | 0 | 1 | OK |
| 54 | 125 | 0 | 0 | 0 | 1 | OK |
| 55 | 639 | 1 | 1 | 0 | 3 | OK |
| 56 | 442 | 0 | 0 | 0 | 12 | OK |
| 57 | 97 | 0 | 0 | 0 | 3 | OK |
| 58 | 125 | 0 | 0 | 0 | 1 | OK |
| 59 | 588 | 0 | 1 | 0 | 3 | OK |
| 60 | 0 | 0 | 0 | 0 | 1 | OK |
| 61 | 1,176 | 0 | 0 | 0 | 2 | OK |
| 62 | 295 | 0 | 0 | 0 | 2 | OK |

### `klia-t2-static-2026.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 55 | 0 | 0 | 0 | 3 | OK |
| 2 | 29 | 0 | 0 | 0 | 3 | OK |
| 3 | 504 | 1 | 3 | 0 | 3 | OK |
| 4 | 56 | 0 | 0 | 0 | 3 | OK |
| 5 | 721 | 1 | 5 | 0 | 4 | OK |
| 6 | 69 | 0 | 0 | 0 | 6 | OK |
| 7 | 487 | 1 | 2 | 0 | 3 | OK |
| 8 | 602 | 1 | 3 | 0 | 3 | OK |
| 9 | 593 | 0 | 2 | 0 | 3 | OK |
| 10 | 702 | 1 | 3 | 0 | 3 | OK |
| 11 | 78 | 0 | 0 | 0 | 3 | OK |
| 12 | 520 | 1 | 3 | 0 | 3 | OK |
| 13 | 519 | 1 | 3 | 0 | 3 | OK |
| 14 | 405 | 1 | 2 | 0 | 4 | OK |
| 15 | 518 | 1 | 3 | 0 | 3 | OK |
| 16 | 426 | 1 | 3 | 0 | 3 | OK |
| 17 | 485 | 1 | 2 | 0 | 3 | OK |
| 18 | 482 | 0 | 2 | 0 | 3 | OK |
| 19 | 467 | 1 | 2 | 0 | 3 | OK |
| 20 | 484 | 1 | 3 | 0 | 3 | OK |
| 21 | 539 | 1 | 3 | 0 | 3 | OK |
| 22 | 74 | 0 | 0 | 0 | 3 | OK |
| 23 | 460 | 0 | 2 | 0 | 3 | OK |
| 24 | 464 | 1 | 2 | 0 | 3 | OK |
| 25 | 472 | 1 | 2 | 0 | 3 | OK |
| 26 | 612 | 1 | 3 | 0 | 3 | OK |
| 27 | 614 | 1 | 3 | 0 | 3 | OK |
| 28 | 597 | 1 | 1 | 0 | 3 | OK |
| 29 | 31 | 0 | 0 | 0 | 4 | OK |
| 30 | 1,176 | 0 | 0 | 0 | 2 | OK |
| 31 | 295 | 0 | 0 | 0 | 2 | OK |

### `konsert-kita-suara-anti-buli-2026.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 211 | 0 | 0 | 0 | 2 | OK |
| 2 | 417 | 0 | 0 | 0 | 3 | OK |
| 3 | 265 | 0 | 0 | 0 | 5 | OK |
| 4 | 614 | 0 | 0 | 0 | 7 | OK |
| 5 | 385 | 0 | 0 | 0 | 3 | OK |
| 6 | 616 | 0 | 0 | 0 | 1 | OK |
| 7 | 815 | 0 | 1 | 0 | 8 | OK |
| 8 | 319 | 0 | 0 | 0 | 3 | OK |
| 9 | 68 | 0 | 0 | 0 | 2 | OK |

### `ktm-train-kl-sentral-ads-2026.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 527 | 0 | 7 | 0 | 4 | OK |
| 2 | 450 | 0 | 4 | 0 | 4 | OK |
| 3 | 628 | 0 | 5 | 0 | 7 | OK |
| 4 | 172 | 0 | 2 | 0 | 3 | OK |
| 5 | 700 | 1 | 0 | 0 | 0 | OK |

### `lamppost-digital-bunting-kl-2026.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 594 | 0 | 0 | 0 | 10 | OK |
| 2 | 508 | 0 | 0 | 0 | 1 | OK |
| 3 | 257 | 0 | 0 | 0 | 2 | OK |
| 4 | 363 | 0 | 0 | 0 | 2 | OK |
| 5 | 343 | 0 | 0 | 0 | 1 | OK |
| 6 | 852 | 0 | 0 | 0 | 10 | OK |

### `malaysiakini-kinitv-event-coverage-2026-2027.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 87 | 0 | 0 | 0 | 3 | OK |
| 2 | 271 | 0 | 0 | 0 | 4 | OK |
| 3 | 279 | 0 | 0 | 0 | 4 | OK |
| 4 | 600 | 0 | 0 | 0 | 6 | OK |
| 5 | 557 | 0 | 0 | 0 | 6 | OK |
| 6 | 1,355 | 0 | 9 | 0 | 0 | OK |
| 7 | 42 | 0 | 0 | 0 | 1 | OK |
| 8 | 574 | 0 | 0 | 0 | 4 | OK |

### `malaysiakini-media-kit-2024.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 0 | 0 | 0 | 0 | 1 | OK |
| 2 | 310 | 0 | 0 | 0 | 11 | OK |
| 3 | 374 | 0 | 0 | 0 | 9 | OK |
| 4 | 297 | 0 | 0 | 0 | 5 | OK |
| 5 | 54 | 0 | 0 | 0 | 4 | OK |
| 6 | 308 | 0 | 0 | 0 | 10 | OK |
| 7 | 454 | 2 | 0 | 0 | 7 | OK |
| 8 | 281 | 0 | 0 | 0 | 5 | OK |
| 9 | 215 | 0 | 0 | 0 | 6 | OK |
| 10 | 88 | 0 | 0 | 0 | 4 | OK |
| 11 | 794 | 0 | 2 | 0 | 0 | OK |
| 12 | 750 | 0 | 0 | 0 | 11 | OK |
| 13 | 243 | 0 | 0 | 0 | 11 | OK |
| 14 | 440 | 0 | 0 | 0 | 10 | OK |
| 15 | 457 | 0 | 0 | 0 | 10 | OK |
| 16 | 620 | 0 | 0 | 0 | 21 | OK |
| 17 | 198 | 0 | 0 | 0 | 7 | OK |
| 18 | 1,045 | 1 | 10 | 0 | 0 | OK |
| 19 | 428 | 0 | 0 | 0 | 5 | OK |
| 20 | 581 | 0 | 0 | 0 | 4 | OK |
| 21 | 136 | 0 | 0 | 0 | 2 | OK |
| 22 | 559 | 0 | 0 | 0 | 9 | OK |
| 23 | 595 | 1 | 3 | 0 | 0 | OK |
| 24 | 642 | 0 | 0 | 0 | 10 | OK |
| 25 | 972 | 0 | 3 | 0 | 0 | OK |
| 26 | 396 | 0 | 0 | 0 | 7 | OK |
| 27 | 751 | 1 | 3 | 0 | 0 | OK |
| 28 | 217 | 0 | 0 | 0 | 10 | OK |
| 29 | 825 | 1 | 3 | 0 | 0 | OK |
| 30 | 423 | 0 | 0 | 0 | 6 | OK |
| 31 | 1,100 | 1 | 3 | 0 | 0 | OK |
| 32 | 68 | 0 | 0 | 0 | 4 | OK |
| 33 | 442 | 0 | 0 | 0 | 0 | OK |
| 34 | 262 | 0 | 0 | 0 | 1 | OK |
| 35 | 296 | 0 | 0 | 0 | 1 | OK |
| 36 | 265 | 0 | 0 | 0 | 1 | OK |
| 37 | 292 | 0 | 0 | 0 | 2 | OK |
| 38 | 937 | 1 | 8 | 0 | 0 | OK |
| 39 | 101 | 0 | 0 | 0 | 3 | OK |
| 40 | 135 | 0 | 0 | 0 | 5 | OK |
| 41 | 964 | 0 | 0 | 0 | 0 | OK |
| 42 | 909 | 0 | 0 | 0 | 0 | OK |
| 43 | 369 | 0 | 0 | 0 | 6 | OK |
| 44 | 875 | 1 | 4 | 0 | 0 | OK |
| 45 | 624 | 0 | 0 | 0 | 4 | OK |

### `media-prima-audio-trade-show-2025.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 2,824 | 1 | 3 | 0 | 5 | OK |

### `mobile-truck-activation-life-events.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 36 | 0 | 0 | 0 | 2 | OK |
| 2 | 258 | 0 | 0 | 0 | 2 | OK |
| 3 | 349 | 0 | 0 | 0 | 1 | OK |
| 4 | 378 | 0 | 4 | 0 | 2 | OK |
| 5 | 186 | 0 | 0 | 0 | 2 | OK |
| 6 | 500 | 0 | 0 | 0 | 2 | OK |

### `omnia-content-terhangat-2026.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 53 | 0 | 0 | 0 | 3 | OK |
| 2 | 966 | 1 | 0 | 0 | 2 | OK |
| 3 | 2,422 | 0 | 16 | 0 | 6 | OK |
| 4 | 2,386 | 0 | 16 | 0 | 6 | OK |
| 5 | 2,599 | 0 | 16 | 0 | 6 | OK |
| 6 | 2,801 | 0 | 16 | 0 | 6 | OK |
| 7 | 2,328 | 0 | 16 | 0 | 6 | OK |
| 8 | 2,499 | 0 | 16 | 0 | 6 | OK |
| 9 | 2,367 | 0 | 16 | 0 | 6 | OK |
| 10 | 2,262 | 2 | 0 | 0 | 6 | OK |
| 11 | 4,892 | 0 | 6 | 0 | 0 | OK |
| 12 | 91 | 0 | 0 | 0 | 3 | OK |
| 13 | 1,986 | 4 | 7 | 0 | 40 | OK |
| 14 | 1,205 | 3 | 0 | 0 | 3 | OK |
| 15 | 1,062 | 3 | 0 | 0 | 3 | OK |
| 16 | 893 | 2 | 0 | 0 | 5 | OK |
| 17 | 1,233 | 3 | 0 | 0 | 6 | OK |
| 18 | 2,350 | 6 | 0 | 0 | 26 | OK |
| 19 | 2,167 | 4 | 0 | 0 | 26 | OK |
| 20 | 1,785 | 5 | 0 | 0 | 30 | OK |
| 21 | 1,720 | 3 | 0 | 0 | 1 | OK |
| 22 | 1,015 | 2 | 0 | 0 | 1 | OK |
| 23 | 35 | 0 | 0 | 0 | 2 | OK |
| 24 | 0 | 0 | 0 | 0 | 3 | OK |

### `podcast-utama-buletin-tv3.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 0 | 0 | 0 | 0 | 11 | OK |
| 2 | 222 | 0 | 0 | 0 | 10 | OK |
| 3 | 253 | 0 | 0 | 0 | 6 | OK |
| 4 | 454 | 0 | 0 | 0 | 1 | OK |
| 5 | 482 | 0 | 0 | 0 | 8 | OK |
| 6 | 315 | 0 | 0 | 0 | 40 | OK |
| 7 | 254 | 0 | 0 | 0 | 3 | OK |
| 8 | 450 | 0 | 0 | 0 | 16 | OK |
| 9 | 608 | 0 | 0 | 0 | 44 | OK |
| 10 | 340 | 0 | 0 | 0 | 32 | OK |
| 11 | 325 | 0 | 0 | 0 | 28 | OK |
| 12 | 685 | 0 | 0 | 0 | 16 | OK |
| 13 | 799 | 0 | 0 | 0 | 15 | OK |
| 14 | 764 | 0 | 2 | 0 | 20 | OK |
| 15 | 865 | 0 | 2 | 0 | 26 | OK |
| 16 | 601 | 0 | 0 | 0 | 28 | OK |
| 17 | 995 | 0 | 0 | 0 | 0 | OK |
| 18 | 11 | 0 | 0 | 0 | 11 | OK |

### `ra-media-relations-live-tv-talkshow-2026-2027.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 1,501 | 4 | 20 | 0 | 1 | OK |

### `ra-media-relations-news-slot-2026-2027.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 853 | 1 | 14 | 0 | 1 | OK |

### `ra-media-website-media-solutions-2026.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 1,124 | 2 | 0 | 0 | 2 | OK |
| 2 | 2,380 | 1 | 0 | 0 | 0 | OK |
| 3 | 1,723 | 1 | 0 | 0 | 0 | OK |
| 4 | 1,744 | 1 | 0 | 0 | 0 | OK |
| 5 | 2,829 | 1 | 0 | 0 | 0 | OK |
| 6 | 2,750 | 1 | 0 | 0 | 0 | OK |
| 7 | 2,783 | 1 | 0 | 0 | 0 | OK |
| 8 | 3,069 | 1 | 0 | 0 | 0 | OK |
| 9 | 1,082 | 1 | 0 | 0 | 0 | OK |
| 10 | 620 | 1 | 0 | 0 | 0 | OK |
| 11 | 2,170 | 0 | 0 | 0 | 0 | OK |
| 12 | 2,223 | 0 | 0 | 0 | 0 | OK |
| 13 | 3,123 | 1 | 0 | 0 | 0 | OK |
| 14 | 2,877 | 1 | 0 | 0 | 0 | OK |
| 15 | 2,672 | 1 | 0 | 0 | 0 | OK |
| 16 | 2,472 | 1 | 0 | 0 | 0 | OK |
| 17 | 2,182 | 1 | 0 | 0 | 0 | OK |
| 18 | 1,765 | 1 | 0 | 0 | 0 | OK |
| 19 | 1,372 | 1 | 0 | 0 | 0 | OK |
| 20 | 1,685 | 1 | 3 | 0 | 0 | OK |
| 21 | 2,033 | 1 | 6 | 0 | 0 | OK |
| 22 | 1,823 | 0 | 18 | 0 | 0 | OK |
| 23 | 1,744 | 0 | 22 | 0 | 0 | OK |
| 24 | 1,337 | 0 | 18 | 0 | 0 | OK |
| 25 | 1,437 | 0 | 30 | 0 | 0 | OK |
| 26 | 1,344 | 0 | 23 | 0 | 0 | OK |
| 27 | 1,911 | 1 | 0 | 0 | 0 | OK |
| 28 | 1,913 | 0 | 0 | 0 | 0 | OK |

### `rtm-tv-radio-2025.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 53 | 0 | 0 | 0 | 3 | OK |
| 2 | 1,101 | 1 | 0 | 0 | 0 | OK |
| 3 | 198 | 0 | 0 | 0 | 4 | OK |
| 4 | 182 | 1 | 0 | 0 | 4 | OK |
| 5 | 117 | 1 | 0 | 0 | 4 | OK |
| 6 | 117 | 0 | 0 | 0 | 10 | OK |
| 7 | 396 | 0 | 1 | 0 | 4 | OK |
| 8 | 544 | 0 | 2 | 0 | 0 | OK |
| 9 | 380 | 0 | 1 | 0 | 6 | OK |
| 10 | 540 | 1 | 0 | 0 | 1 | OK |
| 11 | 437 | 1 | 0 | 0 | 1 | OK |
| 12 | 243 | 0 | 2 | 0 | 0 | OK |
| 13 | 319 | 1 | 1 | 0 | 0 | OK |
| 14 | 548 | 0 | 2 | 0 | 0 | OK |
| 15 | 192 | 1 | 0 | 0 | 0 | OK |
| 16 | 396 | 1 | 12 | 0 | 0 | OK |
| 17 | 224 | 1 | 4 | 0 | 0 | OK |
| 18 | 246 | 1 | 2 | 0 | 0 | OK |
| 19 | 262 | 0 | 2 | 0 | 0 | OK |
| 20 | 383 | 0 | 2 | 0 | 0 | OK |
| 21 | 292 | 1 | 1 | 0 | 0 | OK |
| 22 | 813 | 1 | 0 | 0 | 2 | OK |
| 23 | 810 | 1 | 0 | 0 | 0 | OK |
| 24 | 763 | 2 | 0 | 0 | 0 | OK |
| 25 | 890 | 2 | 0 | 0 | 0 | OK |
| 26 | 443 | 1 | 0 | 0 | 0 | OK |
| 27 | 646 | 2 | 0 | 0 | 0 | OK |
| 28 | 540 | 1 | 0 | 0 | 0 | OK |
| 29 | 434 | 1 | 0 | 0 | 0 | OK |
| 30 | 32 | 0 | 0 | 0 | 0 | OK |
| 31 | 605 | 1 | 0 | 0 | 0 | OK |
| 32 | 608 | 1 | 0 | 0 | 0 | OK |
| 33 | 596 | 1 | 0 | 0 | 0 | OK |
| 34 | 656 | 1 | 0 | 0 | 0 | OK |
| 35 | 671 | 1 | 0 | 0 | 0 | OK |
| 36 | 646 | 1 | 0 | 0 | 0 | OK |
| 37 | 566 | 1 | 0 | 0 | 0 | OK |
| 38 | 562 | 1 | 0 | 0 | 0 | OK |
| 39 | 556 | 1 | 0 | 0 | 0 | OK |
| 40 | 340 | 1 | 0 | 0 | 0 | OK |
| 41 | 171 | 0 | 0 | 0 | 0 | OK |
| 42 | 21 | 0 | 0 | 0 | 0 | OK |
| 43 | 295 | 1 | 0 | 0 | 0 | OK |
| 44 | 539 | 1 | 0 | 0 | 0 | OK |
| 45 | 509 | 1 | 0 | 0 | 0 | OK |
| 46 | 647 | 2 | 0 | 0 | 0 | OK |
| 47 | 553 | 2 | 0 | 0 | 0 | OK |
| 48 | 315 | 1 | 0 | 0 | 0 | OK |
| 49 | 167 | 0 | 2 | 0 | 0 | OK |
| 50 | 155 | 0 | 0 | 0 | 4 | OK |

### `sinar-harian-20th-anniversary-2026.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 2,585 | 1 | 0 | 0 | 18 | OK |
| 2 | 1,955 | 1 | 0 | 0 | 46 | OK |
| 3 | 2,235 | 4 | 12 | 0 | 108 | OK |

### `sinar-karangkraf-ratecard-2025-2026.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 0 | 0 | 0 | 0 | 2 | OK |
| 2 | 0 | 0 | 0 | 0 | 1 | OK |
| 3 | 355 | 0 | 0 | 0 | 90 | OK |
| 4 | 67 | 0 | 0 | 0 | 214 | OK |
| 5 | 667 | 1 | 0 | 0 | 11 | OK |
| 6 | 1,568 | 0 | 0 | 0 | 14 | OK |
| 7 | 581 | 0 | 2 | 0 | 33 | OK |
| 8 | 453 | 0 | 0 | 0 | 24 | OK |
| 9 | 293 | 0 | 0 | 0 | 16 | OK |
| 10 | 1,224 | 1 | 0 | 0 | 4 | OK |
| 11 | 973 | 1 | 0 | 0 | 11 | OK |
| 12 | 2,762 | 5 | 17 | 0 | 0 | OK |
| 13 | 2,631 | 1 | 17 | 0 | 0 | OK |
| 14 | 3,180 | 6 | 14 | 0 | 0 | OK |
| 15 | 1,240 | 0 | 0 | 0 | 47 | OK |
| 16 | 393 | 0 | 0 | 0 | 23 | OK |
| 17 | 303 | 0 | 0 | 0 | 31 | OK |
| 18 | 1,423 | 0 | 7 | 0 | 19 | OK |
| 19 | 1,433 | 1 | 30 | 0 | 0 | OK |
| 20 | 1,430 | 2 | 18 | 0 | 0 | OK |
| 21 | 1,292 | 0 | 0 | 0 | 59 | OK |
| 22 | 399 | 0 | 0 | 0 | 21 | OK |
| 23 | 313 | 0 | 0 | 0 | 20 | OK |
| 24 | 374 | 0 | 0 | 0 | 16 | OK |
| 25 | 1,732 | 1 | 5 | 0 | 0 | OK |
| 26 | 1,287 | 0 | 0 | 0 | 10 | OK |
| 27 | 383 | 0 | 0 | 0 | 22 | OK |
| 28 | 305 | 0 | 0 | 0 | 18 | OK |
| 29 | 1,381 | 0 | 0 | 0 | 24 | OK |
| 30 | 1,447 | 1 | 0 | 0 | 15 | OK |
| 31 | 1,471 | 2 | 4 | 0 | 0 | OK |
| 32 | 1,637 | 2 | 10 | 0 | 0 | OK |
| 33 | 7,592 | 0 | 0 | 0 | 170 | OK |
| 34 | 7,347 | 0 | 9 | 0 | 346 | OK |
| 35 | 214 | 0 | 0 | 0 | 13 | OK |
| 36 | 151 | 0 | 0 | 0 | 13 | OK |
| 37 | 3,495 | 0 | 0 | 0 | 0 | OK |
| 38 | 0 | 0 | 0 | 0 | 1 | OK |

### `suria-fm-2026-radio-digital.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 4,057 | 0 | 1 | 0 | 3 | OK |
| 2 | 1,682 | 0 | 6 | 0 | 2 | OK |
| 3 | 123 | 0 | 0 | 0 | 2 | OK |
| 4 | 82 | 0 | 0 | 0 | 2 | OK |
| 5 | 82 | 0 | 0 | 0 | 2 | OK |
| 6 | 82 | 0 | 0 | 0 | 2 | OK |

### `target-media-elevator-2026.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 1,753 | 2 | 7 | 0 | 1 | OK |

### `tmr-super-deal-2026-business-content.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 368 | 0 | 0 | 0 | 10 | OK |
| 2 | 854 | 0 | 0 | 0 | 13 | OK |
| 3 | 683 | 0 | 0 | 0 | 8 | OK |
| 4 | 788 | 0 | 3 | 0 | 6 | OK |
| 5 | 836 | 0 | 3 | 0 | 10 | OK |
| 6 | 1,151 | 0 | 9 | 0 | 12 | OK |
| 7 | 1,334 | 0 | 8 | 0 | 6 | OK |
| 8 | 488 | 0 | 0 | 0 | 4 | OK |

### `tvalhijrah-bincang-niaga-2024.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 0 | 0 | 0 | 0 | 1 | Vision checked |
| 2 | 0 | 0 | 0 | 0 | 1 | Vision checked |
| 3 | 0 | 0 | 0 | 0 | 1 | Vision checked |
| 4 | 0 | 0 | 0 | 0 | 1 | Vision checked |
| 5 | 0 | 0 | 0 | 0 | 1 | Vision checked |

### `tvs-profile-2026.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 64 | 0 | 0 | 0 | 2 | OK |
| 2 | 757 | 0 | 0 | 0 | 5 | OK |
| 3 | 401 | 0 | 0 | 0 | 10 | OK |
| 4 | 118 | 0 | 0 | 0 | 5 | OK |
| 5 | 246 | 0 | 0 | 0 | 15 | OK |
| 6 | 313 | 0 | 0 | 0 | 12 | OK |
| 7 | 566 | 0 | 0 | 0 | 9 | OK |
| 8 | 655 | 0 | 0 | 0 | 5 | OK |
| 9 | 247 | 0 | 0 | 0 | 2 | OK |
| 10 | 73 | 0 | 0 | 0 | 2 | OK |
| 11 | 819 | 0 | 0 | 0 | 9 | OK |
| 12 | 532 | 0 | 0 | 0 | 8 | OK |
| 13 | 89 | 0 | 0 | 0 | 3 | OK |
| 14 | 2,044 | 2 | 34 | 0 | 1 | OK |
| 15 | 1,430 | 3 | 24 | 0 | 2 | OK |
| 16 | 1,250 | 0 | 7 | 0 | 3 | OK |
| 17 | 1,248 | 1 | 7 | 0 | 4 | OK |
| 18 | 1,243 | 2 | 7 | 0 | 4 | OK |
| 19 | 169 | 0 | 0 | 0 | 2 | OK |
| 20 | 1,181 | 0 | 13 | 0 | 1 | OK |
| 21 | 542 | 0 | 0 | 0 | 1 | OK |

### `tvs-sponsorship-spot-buy-2026.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 34 | 0 | 0 | 0 | 2 | OK |
| 2 | 37 | 0 | 0 | 0 | 1 | OK |
| 3 | 584 | 0 | 0 | 0 | 45 | OK |
| 4 | 23 | 0 | 0 | 0 | 2 | OK |
| 5 | 258 | 0 | 3 | 0 | 9 | OK |
| 6 | 457 | 0 | 2 | 0 | 7 | OK |
| 7 | 327 | 0 | 2 | 0 | 10 | OK |
| 8 | 328 | 0 | 2 | 0 | 8 | OK |
| 9 | 403 | 0 | 2 | 0 | 9 | OK |
| 10 | 257 | 0 | 2 | 0 | 10 | OK |
| 11 | 534 | 0 | 2 | 0 | 8 | OK |
| 12 | 242 | 0 | 2 | 0 | 7 | OK |
| 13 | 438 | 0 | 1 | 0 | 8 | OK |
| 14 | 470 | 0 | 1 | 0 | 8 | OK |
| 15 | 436 | 0 | 2 | 0 | 6 | OK |
| 16 | 847 | 0 | 2 | 0 | 5 | OK |
| 17 | 154 | 0 | 2 | 0 | 5 | OK |
| 18 | 169 | 0 | 3 | 0 | 6 | OK |
| 19 | 165 | 0 | 4 | 0 | 5 | OK |
| 20 | 265 | 0 | 4 | 0 | 6 | OK |
| 21 | 813 | 0 | 10 | 0 | 4 | OK |
| 22 | 805 | 0 | 8 | 0 | 13 | OK |
| 23 | 562 | 0 | 2 | 0 | 4 | OK |

### `wowshop-airtime-2025.pdf`

| Page | Text chars | Tables | Price values | Missing | Images | Status |
| ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | 42 | 0 | 0 | 0 | 5 | OK |
| 2 | 0 | 0 | 0 | 0 | 7 | OK |
| 3 | 283 | 0 | 0 | 0 | 9 | OK |
| 4 | 182 | 0 | 0 | 0 | 14 | OK |
| 5 | 14 | 0 | 0 | 0 | 5 | OK |
| 6 | 200 | 0 | 0 | 0 | 13 | OK |
| 7 | 290 | 0 | 0 | 0 | 13 | OK |
| 8 | 98 | 0 | 0 | 0 | 6 | OK |
| 9 | 12 | 0 | 0 | 0 | 5 | OK |
| 10 | 226 | 0 | 0 | 0 | 8 | OK |
| 11 | 12 | 0 | 0 | 0 | 5 | OK |
| 12 | 430 | 0 | 0 | 0 | 6 | OK |
| 13 | 756 | 0 | 0 | 0 | 11 | OK |
| 14 | 245 | 0 | 0 | 0 | 37 | OK |
| 15 | 15 | 0 | 0 | 0 | 5 | OK |
| 16 | 240 | 0 | 0 | 0 | 11 | OK |
| 17 | 17 | 0 | 0 | 0 | 5 | OK |
| 18 | 248 | 0 | 0 | 0 | 15 | OK |
| 19 | 35 | 0 | 0 | 0 | 5 | OK |
| 20 | 861 | 0 | 1 | 0 | 2 | OK |
| 21 | 597 | 0 | 9 | 0 | 8 | OK |
| 22 | 561 | 0 | 8 | 0 | 6 | OK |
| 23 | 449 | 0 | 6 | 0 | 4 | OK |
| 24 | 572 | 0 | 8 | 0 | 4 | OK |
| 25 | 486 | 0 | 7 | 0 | 3 | OK |
| 26 | 745 | 1 | 9 | 0 | 4 | OK |
| 27 | 32 | 0 | 0 | 0 | 5 | OK |
| 28 | 166 | 0 | 0 | 0 | 14 | OK |
| 29 | 0 | 0 | 0 | 0 | 2 | OK |

## Superseded KLIA 2024 decks (archived, not current)

These decks remain in the private reference archive but are not part of the current public catalogue. Their content was replaced by the 2026 KLIA static and digital decks. They are recorded here so nothing is silently ignored.

| PDF | Pages | Table pages | Price values | Unmatched | Reason |
| --- | ---: | ---: | ---: | ---: | --- |
| `klia-t1-airport-2024-premium.pdf` | 42 | 8 | 29 | 29 | Superseded by 2026 KLIA decks; archived reference only |
| `klia-t2-airport-2024.pdf` | 41 | 8 | 38 | 38 | Superseded by 2026 KLIA decks; archived reference only |

## Visual assets

- 361 gallery images live under `public/assets/ratecards/images/` and `public/assets/official/`, seeded via `RateCardGallerySeeder` from `resources/data/rate-card-gallery.json` plus `resources/data/rate-card-official-gallery.json`.
- 27 official/CC-licensed brand images were added for RTM, Bernama, TV3, Astro Oasis, 988 FM, Suria FM, TV AlHijrah, Target Media, Sinar Harian, Malaysiakini, Sarawak, KLIA Ekspres, KTM and KL Sentral. Generated brand galleries were removed. Any generated concept art is now labelled **Illustration** and excluded from “Inside [brand]” galleries.
- Gallery images are cropped/embedded source-deck photography or official/CC-licensed brand imagery with source links. Generated imagery is not presented as actual coverage; no PDF page is served to visitors.
- Official brand images remain the property of their respective owners and are used here for editorial reference with visible source links. Wikimedia Commons images retain the licence and credit shown on each image.

## Reproduce

```bash
python3 scripts/extract-rate-card-gallery.py
php artisan migrate:fresh --seed
php artisan test --compact tests/Feature/RateCardCoverageTest.php tests/Feature/PublicNoPdfTest.php
```
