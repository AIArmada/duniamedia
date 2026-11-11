# Work Archive — spreadsheet link audit

Sources scanned:

- `List of Clients March 2026_Advertising.xlsx` (Govt Agency + Non Govt, 953/961 rows)
- `List of Clients March 2026_Advertising 2.xlsx` (duplicate copy)
- `List of Clients March 2026_Video N Event.xlsx`
- `List of talkshow clients_250226.xlsx`
- The three rate-card XLSX workbooks in private storage

No `.xls` or `.doc`/`.docx` files exist in either Drive folder.

## Links found

| Sheet | Hyperlinks | Text URLs | Notes |
|---|---:|---:|---|
| Advertising — Govt Agency | 44 | 34 | Mostly mailto, YouTube, Instagram, TikTok, Facebook, news coverage and 4 Drive files |
| Advertising — Non Govt | 0 | 0 | Client/contact rows only |
| Video N Event | 11 | 4 | 2 Drive folders + 2 Drive files, rest mailto |
| Talkshow clients | 6 | 6 | 4 Drive files + Instagram + expired WeTransfer |
| Rate-card XLSX | 2 | 0 | `mailto:` only; no external links |

## Drive media resolved

| Source | Result | Hosted as |
|---|---|---|
| JWP PSA folder — Kuala Lumpur | 30s, 1080p, downloadable | `jwp-psa-kl` |
| JWP PSA folder — Putrajaya | 30s, 1080p, downloadable | `jwp-psa-putrajaya` |
| JWP PSA folder — Labuan | 30s, 1080p, downloadable | `jwp-psa-labuan` |
| My Grocer — WAKEEL | 2:45, 1080p master, downloadable | `jwp-wakeel-my-grocer` |
| KPJ Kajang — TV9, 2 Oct 2025 | 6:51 broadcast | `kpj-2025-10-02` (90s highlight) |
| KPJ Healthcare Kajang — TV9, 26 Nov 2025 | 8:58 broadcast | `kpj-2025-11-26` (90s highlight) |
| KPJ Kajang — TV9, 18 Dec 2025 | 8:35 broadcast | `kpj-2025-12-18` (90s highlight) |
| Suruhanjaya Tenaga — NLKO, 6 Feb 2025 | 17:30 broadcast | `st-nlko-2025` (2 min highlight) |

All eight were transcoded to 720p H.264 with AAC audio, fast-start enabled, and stored in
`storage/app/private/work/`. They stream through `/work/media/{key}` with HTTP range support.

The legacy Yayasan Maghfirah 30-second film and the JWP PASPORT50WP WAV voiceover were
recovered from the S3 media bucket and restored, bringing the gallery to 10 hosted items.
Posters are frames from the films in `public/assets/work/covers/`.

## Dead or unusable links

- JWP `PASPORT50WP` Drive folder — 404
- JWP `LESTARI NIAGA` Drive file — 404
- WeTransfer download — expired
- Several KLIA deck links — 404 (noted in `RATECARDS-2026.md`)

## Campaign extraction

The spreadsheets contain 25 unique campaign candidates. Comparing them with
`resources/data/client-work.json` (53 campaigns) showed that the apparent additions were
title/casing variants of campaigns already in the archive. No new unique campaigns were needed.

Four archive campaigns now link to their hosted films with “Watch the film”:

- `PSA 30-Second Capsules` → three JWP PSAs
- `My Grocer — WAKEEL` → WAKEEL PSA
- `Special Interview — KPJ Kajang` → three TV9 highlights
- `Ketahui Hak Anda Sebagai Pengguna` → Suruhanjaya Tenaga highlight

Social, news and TikTok/Instagram links were treated as coverage evidence only and were not
added as external links, keeping the app self-contained.
