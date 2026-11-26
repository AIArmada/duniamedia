#!/usr/bin/env python3
"""Replace brand-specific generated heroes with official or CC-licensed images."""

import json
import os
from PIL import Image, ImageDraw, ImageOps

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
OUT = os.path.join(ROOT, 'public/assets/ratecards/img')
SOURCES = os.path.join(ROOT, 'resources/data/official-media-sources.json')
W, H = 1536, 1024
SOURCES_DATA = {}


def crop_cover(image):
    ratio = W / H
    width, height = image.size
    if width / height > ratio:
        new_width = round(height * ratio)
        left = (width - new_width) // 2
        return image.crop((left, 0, left + new_width, height))
    new_height = round(width / ratio)
    top = (height - new_height) // 2
    return image.crop((0, top, width, top + new_height))


def save(image, slug):
    image = image.resize((W, H), Image.LANCZOS)
    for target_width in (W, 960, 480):
        variant = image.resize((target_width, round(H * target_width / W)), Image.LANCZOS)
        suffix = '' if target_width == W else f'-{target_width}'
        variant.save(os.path.join(OUT, f'{slug}{suffix}.webp'), 'WEBP', quality=84, method=6)


def photo(source, slug, source_url, source_label, credit):
    save(crop_cover(Image.open(source).convert('RGB')), slug)
    SOURCES_DATA[slug] = {'source_url': source_url, 'source_label': source_label, 'credit': credit, 'kind': 'official'}


def logo_card(logo_path, slug, background, source_url, source_label, credit):
    canvas = Image.new('RGB', (W, H), background)
    draw = ImageDraw.Draw(canvas)
    draw.rectangle((0, 0, W, 8), fill='white')
    logo = Image.open(logo_path).convert('RGBA')
    logo.thumbnail((900, 500), Image.LANCZOS)
    x = (W - logo.width) // 2
    y = (H - logo.height) // 2
    canvas.paste(logo, (x, y), logo)
    save(canvas, slug)
    SOURCES_DATA[slug] = {'source_url': source_url, 'source_label': source_label, 'credit': credit, 'kind': 'official'}


def collage(slug, logos, background, source_url, source_label, credit):
    canvas = Image.new('RGB', (W, H), background)
    tiles = []
    for path in logos:
        logo = Image.open(path).convert('RGBA')
        logo.thumbnail((460, 260), Image.LANCZOS)
        tiles.append(logo)
    gap = 30
    total = sum(t.width for t in tiles) + gap * (len(tiles) - 1)
    x = (W - total) // 2
    for tile in tiles:
        y = (H - tile.height) // 2
        canvas.paste(tile, (x, y), tile)
        x += tile.width + gap
    save(canvas, slug)
    SOURCES_DATA[slug] = {'source_url': source_url, 'source_label': source_label, 'credit': credit, 'kind': 'official'}


def main():
    photo('/tmp/official/rtm-angkasapuri.jpg', 'rtm-tv-radio-2025',
          'https://commons.wikimedia.org/wiki/File:Angkasapuri_April_30_2018.jpg',
          'Wikimedia Commons', 'Photo: PulauKakatua19 / Wikimedia Commons, CC BY-SA 4.0')
    photo('/tmp/official/bernama-building.jpg', 'bernama-media-kit-2026',
          'https://commons.wikimedia.org/wiki/File:Bernama.jpg',
          'Wikimedia Commons', 'Photo: Wikimedia Commons contributor, CC BY-SA 4.0')
    photo('/tmp/official/mediaprima-sri-pentas.jpg', 'omnia-content-terhangat-2026',
          'https://commons.wikimedia.org/wiki/File:Sri_Pentas,_Bandar_Utama_20260410_150038.jpg',
          'Wikimedia Commons', 'Photo: Wiki Farazi / Wikimedia Commons, CC0')
    photo('/tmp/official/astro-oasis-banner.jpg', 'astro-upfront-2027',
          'https://content.astro.com.my/channels/Astro-Oasis-315',
          'Astro', 'Official Astro Oasis channel artwork')
    photo('/tmp/official/astro-logo.png', 'astro-video360-digital-2026',
          'https://www.astro.com.my/', 'Astro', 'Official Astro brand mark')
    photo('/tmp/official/988-banner.jpg', '988-fm-2026-radio-digital',
          'https://988.com.my/', '988 FM', 'Official 988 FM campaign artwork')
    photo('/tmp/official/suria-pagi.jpg', 'suria-fm-2026-radio-digital',
          'https://suria.my/segmen/pagi-on-suria/', 'Suria FM', 'Official Suria FM show artwork')
    logo_card(os.path.join(ROOT, 'public/assets/partners/mp-hotfm-v2.png'), 'hot-fm-zoomerz-on-ground',
              '#ffffff', 'https://www.mediaprima.audio/portfolio/hot-fm/', 'Media Prima Audio', 'Official Hot FM brand mark')
    photo('/tmp/official/tvalhijrah-building.jpg', 'tvalhijrah-bincang-niaga-2024',
          'https://commons.wikimedia.org/wiki/File:TV_ALHIJRAH_building_in_KL_(May_2022).jpg',
          'Wikimedia Commons', 'Photo: Chainwit. / Wikimedia Commons, CC BY-SA 4.0')
    photo('/tmp/official/target-4.jpg', 'target-media-elevator-q2-2025',
          'https://targetmedia.my/', 'Target Media', 'Official Target Media portfolio photograph')
    logo_card('/tmp/official/logo-tmr-wordmark.png', 'tmr-super-deal-2026-business-content',
              '#f4efe6', 'https://themalaysianreserve.com/', 'The Malaysian Reserve', 'Official The Malaysian Reserve wordmark')
    logo_card('/tmp/official/ra-0.png', 'ra-media-website-media-solutions-2026',
              '#0d0d0d', 'https://ramediarelations.wixsite.com/website', 'RA Media Relations', 'Official RA Media wordmark')
    logo_card('/tmp/official/ra-0.png', 'ra-media-relations-live-tv-talkshow-2026-2027',
              '#0d0d0d', 'https://ramediarelations.wixsite.com/website', 'RA Media Relations', 'Official RA Media wordmark')
    logo_card('/tmp/official/ra-0.png', 'ra-media-relations-news-slot-2026-2027',
              '#0d0d0d', 'https://ramediarelations.wixsite.com/website', 'RA Media Relations', 'Official RA Media wordmark')
    photo('/tmp/official/sinar-2.webp', 'sinar-harian-20th-anniversary-2026',
          'https://www.sinarharian.com.my/', 'Sinar Harian', 'Official Sinar Harian editorial photograph')
    photo('/tmp/official/sinar-2.webp', 'sinar-karangkraf-ratecard-2025-2026',
          'https://www.sinarharian.com.my/', 'Sinar Harian', 'Official Sinar Harian editorial photograph')
    photo('/tmp/official/malaysiakini.jpg', 'malaysiakini-media-kit-2024',
          'https://www.malaysiakini.com/en/about', 'Malaysiakini', 'Official Malaysiakini image')
    photo('/tmp/official/malaysiakini.jpg', 'malaysiakini-kinitv-event-coverage-2026-2027',
          'https://www.malaysiakini.com/en/about', 'Malaysiakini', 'Official Malaysiakini image')
    photo('/tmp/official/kuching-waterfront.jpg', 'billboard-sarawak-smg-2026',
          'https://commons.wikimedia.org/wiki/File:Kuching_Waterfront_(17152369184).jpg',
          'Wikimedia Commons', 'Photo: Wikimedia Commons contributor, CC BY-SA 2.0')
    photo('/tmp/official/klia-ekspres.jpg', 'erl-digital-screens-2026',
          'https://commons.wikimedia.org/wiki/File:KLIA_Ekspres-2015-01-12.jpg',
          'Wikimedia Commons', 'Photo: Bigbug21 / Wikimedia Commons, CC BY-SA 4.0')
    photo('/tmp/official/ktm-train.jpg', 'ktm-train-kl-sentral-ads-2026',
          'https://commons.wikimedia.org/wiki/File:Class_83_KTM_Komuter_train,_Kuala_Lumpur.jpg',
          'Wikimedia Commons', 'Photo: Two hundred percent / Wikimedia Commons, CC BY-SA 2.5')
    photo('/tmp/official/kl-sentral.jpg', 'kl-sentral-station-sponsorship-2024',
          'https://commons.wikimedia.org/wiki/File:KL_Sentral,_Kuala_Lumpur_20221229_150139.jpg',
          'Wikimedia Commons', 'Photo: Wiki Farazi / Wikimedia Commons, CC0')
    photo('/tmp/official/fmt-news.webp', 'fmt-2026-digital-news',
          'https://www.freemalaysiatoday.com/', 'Free Malaysia Today', 'Official FMT editorial photograph')
    logo_card('/tmp/official/logo-ikim.png', 'ikim-fm-ramadan-2027',
              '#0b2b4a', 'https://www.ikim.gov.my/', 'IKIM', 'Official IKIM brand mark')
    logo_card(os.path.join(ROOT, 'public/assets/partners/tvs.png'), 'tvs-sponsorship-spot-buy-2026',
              '#0b7d7a', 'https://commons.wikimedia.org/wiki/File:TVS_logo_(2023).png', 'Wikimedia Commons', 'Official TVS / Sarawak Media Group logo, CC BY-SA 4.0')
    collage('media-prima-audio-trade-show-2025', [
        os.path.join(ROOT, 'public/assets/partners/mp-hotfm-v2.png'),
        os.path.join(ROOT, 'public/assets/partners/star-988.png'),
        os.path.join(ROOT, 'public/assets/partners/star-suria.png'),
    ], '#111111', 'https://www.mediaprima.audio/', 'Media Prima Audio', 'Official Media Prima Audio station marks')
    json.dump(SOURCES_DATA, open(SOURCES, 'w', encoding='utf-8'), ensure_ascii=False, indent=2)
    open(SOURCES, 'a').write('\n')
    print(f'Installed {len(SOURCES_DATA)} official brand heroes')


if __name__ == '__main__':
    main()
