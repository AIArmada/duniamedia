#!/usr/bin/env python3
"""Install official/CC-licensed brand imagery and record provenance."""

import json
import os
from PIL import Image

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
OUT = os.path.join(ROOT, 'public/assets/official')
MANIFEST = os.path.join(ROOT, 'resources/data/rate-card-official-gallery.json')
W, H = 1200, 900

ASSETS = [
    ('rtm-tv-radio-2025', '/tmp/official/rtm-angkasapuri.jpg', 'rtm/angkasapuri.webp',
     'Angkasapuri, the headquarters of Radio Televisyen Malaysia',
     'Radio Televisyen Malaysia headquarters in Kuala Lumpur',
     'https://commons.wikimedia.org/wiki/File:Angkasapuri_April_30_2018.jpg',
     'Wikimedia Commons', 'Photo: PulauKakatua19 / Wikimedia Commons, CC BY-SA 4.0', 'official'),
    ('bernama-media-kit-2026', '/tmp/official/bernama-building.jpg', 'bernama/building.webp',
     'The BERNAMA headquarters building in Kuala Lumpur',
     'BERNAMA national news agency headquarters',
     'https://commons.wikimedia.org/wiki/File:Bernama.jpg',
     'Wikimedia Commons', 'Photo: Wikimedia Commons contributor, CC BY-SA 4.0', 'official'),
    ('omnia-content-terhangat-2026', '/tmp/official/mediaprima-sri-pentas.jpg', 'mediaprima/sri-pentas-1.webp',
     'Sri Pentas, the Media Prima headquarters in Bandar Utama',
     'Media Prima / TV3 headquarters at Sri Pentas',
     'https://commons.wikimedia.org/wiki/File:Sri_Pentas,_Bandar_Utama_20260410_150038.jpg',
     'Wikimedia Commons', 'Photo: Wiki Farazi / Wikimedia Commons, CC0', 'official'),
    ('omnia-content-terhangat-2026', '/tmp/official/mediaprima-sri-pentas-2.jpg', 'mediaprima/sri-pentas-2.webp',
     'The Sri Pentas media complex in Bandar Utama',
     'Media Prima / TV3 headquarters at Sri Pentas',
     'https://commons.wikimedia.org/wiki/File:Sri_Pentas,_Bandar_Utama_20260410_150443.jpg',
     'Wikimedia Commons', 'Photo: Wiki Farazi / Wikimedia Commons, CC0', 'official'),
    ('astro-upfront-2027', '/tmp/official/astro-oasis-banner.jpg', 'astro/oasis-channel.webp',
     'Official Astro Oasis channel banner',
     'Astro Oasis — official channel artwork',
     'https://content.astro.com.my/channels/Astro-Oasis-315',
     'Astro', 'Official Astro Oasis artwork', 'official'),
    ('astro-upfront-2027', '/tmp/official/astro-oasis-programme1.jpg', 'astro/oasis-programme-1.webp',
     'Official Astro Oasis programme artwork',
     'Astro Oasis — official programme artwork',
     'https://content.astro.com.my/channels/Astro-Oasis-315',
     'Astro', 'Official Astro Oasis programme artwork', 'official'),
    ('astro-upfront-2027', '/tmp/official/astro-oasis-programme2.jpg', 'astro/oasis-programme-2.webp',
     'Official Astro Oasis programme artwork',
     'Astro Oasis — official programme artwork',
     'https://content.astro.com.my/channels/Astro-Oasis-315',
     'Astro', 'Official Astro Oasis programme artwork', 'official'),
    ('988-fm-2026-radio-digital', '/tmp/official/988-banner.jpg', '988/campaign-banner.webp',
     'Official 988 FM campaign artwork',
     '988 FM — official campaign artwork',
     'https://988.com.my/',
     '988 FM', 'Official 988 FM artwork', 'official'),
    ('suria-fm-2026-radio-digital', '/tmp/official/suria-pagi.jpg', 'suria/pagi-on-suria.webp',
     'Official Pagi On Suria show banner',
     'Pagi On Suria — official show artwork',
     'https://suria.my/segmen/pagi-on-suria/',
     'Suria FM', 'Official Suria FM artwork', 'official'),
    ('suria-fm-2026-radio-digital', '/tmp/official/suria-vibe.jpg', 'suria/vibe-suria.webp',
     'Official Vibe Suria show artwork',
     'Vibe Suria — official show artwork',
     'https://suria.my/segmen/vibe-suria/',
     'Suria FM', 'Official Suria FM artwork', 'official'),
    ('suria-fm-2026-radio-digital', '/tmp/official/suria-concert.jpg', 'suria/panggung-suria.webp',
     'Official Panggung Suria concert artwork',
     'Panggung Suria — official event artwork',
     'https://suria.my/',
     'Suria FM', 'Official Suria FM artwork', 'official'),
    ('tvalhijrah-bincang-niaga-2024', '/tmp/official/tvalhijrah-building.jpg', 'tvalhijrah/building.webp',
     'TV AlHijrah broadcasting centre in Kuala Lumpur',
     'TV AlHijrah headquarters',
     'https://commons.wikimedia.org/wiki/File:TV_ALHIJRAH_building_in_KL_(May_2022).jpg',
     'Wikimedia Commons', 'Photo: Chainwit. / Wikimedia Commons, CC BY-SA 4.0', 'official'),
    ('tvalhijrah-bincang-niaga-2024', '/tmp/official/tvalhijrah-program.jpg', 'tvalhijrah/programme-2026.webp',
     'Official TV AlHijrah 2026 programme banner',
     'TV AlHijrah — official programme artwork',
     'https://tvalhijrah.com/',
     'TV AlHijrah', 'Official TV AlHijrah artwork', 'official'),
    ('tvalhijrah-bincang-niaga-2024', '/tmp/official/tvalhijrah-quran.jpg', 'tvalhijrah/quran-2026.webp',
     'Official TV AlHijrah Quran programme promotion',
     'TV AlHijrah — official programme artwork',
     'https://tvalhijrah.com/',
     'TV AlHijrah', 'Official TV AlHijrah artwork', 'official'),
    ('target-media-elevator-q2-2025', '/tmp/official/target-1.jpg', 'target/elevator-1.webp',
     'Target Media elevator screen in a Kuala Lumpur office lobby',
     'Target Media — official elevator media photograph',
     'https://targetmedia.my/',
     'Target Media', 'Official Target Media portfolio image', 'official'),
    ('target-media-elevator-q2-2025', '/tmp/official/target-4.jpg', 'target/elevator-2.webp',
     'Audience viewing a Target Media elevator screen',
     'Target Media — official elevator media photograph',
     'https://targetmedia.my/',
     'Target Media', 'Official Target Media portfolio image', 'official'),
    ('target-media-elevator-q2-2025', '/tmp/official/target-5.jpg', 'target/elevator-3.webp',
     'Target Media digital screen in an office lift lobby',
     'Target Media — official elevator media photograph',
     'https://targetmedia.my/',
     'Target Media', 'Official Target Media portfolio image', 'official'),
    ('sinar-harian-20th-anniversary-2026', '/tmp/official/sinar-2.webp', 'sinar/news-1.webp',
     'Sinar Harian official news photograph',
     'Sinar Harian — official editorial photograph',
     'https://www.sinarharian.com.my/',
     'Sinar Harian', 'Official Sinar Harian image', 'official'),
    ('sinar-harian-20th-anniversary-2026', '/tmp/official/sinar-3.webp', 'sinar/news-2.webp',
     'Sinar Harian official news photograph',
     'Sinar Harian — official editorial photograph',
     'https://www.sinarharian.com.my/',
     'Sinar Harian', 'Official Sinar Harian image', 'official'),
    ('sinar-karangkraf-ratecard-2025-2026', '/tmp/official/sinar-1.webp', 'sinar/news-3.webp',
     'Sinar Harian official news photograph',
     'Sinar Harian — official editorial photograph',
     'https://www.sinarharian.com.my/',
     'Sinar Harian', 'Official Sinar Harian image', 'official'),
    ('sinar-karangkraf-ratecard-2025-2026', '/tmp/official/sinar-2.webp', 'sinar/news-4.webp',
     'Sinar Harian official news photograph',
     'Sinar Harian — official editorial photograph',
     'https://www.sinarharian.com.my/',
     'Sinar Harian', 'Official Sinar Harian image', 'official'),
    ('malaysiakini-media-kit-2024', '/tmp/official/malaysiakini.jpg', 'malaysiakini/about.webp',
     'Malaysiakini office and editorial team',
     'Malaysiakini — official about image',
     'https://www.malaysiakini.com/en/about',
     'Malaysiakini', 'Official Malaysiakini image', 'official'),
    ('malaysiakini-kinitv-event-coverage-2026-2027', '/tmp/official/malaysiakini.jpg', 'malaysiakini/about-2.webp',
     'Malaysiakini office and editorial team',
     'Malaysiakini — official about image',
     'https://www.malaysiakini.com/en/about',
     'Malaysiakini', 'Official Malaysiakini image', 'official'),
    ('billboard-sarawak-smg-2026', '/tmp/official/kuching-waterfront.jpg', 'sarawak/kuching-waterfront.webp',
     'Kuching waterfront, Sarawak',
     'Kuching, Sarawak — location context for the Sarawak media market',
     'https://commons.wikimedia.org/wiki/File:Kuching_Waterfront_(17152369184).jpg',
     'Wikimedia Commons', 'Photo: Wikimedia Commons contributor, CC BY-SA 2.0', 'official'),
    ('erl-digital-screens-2026', '/tmp/official/klia-ekspres.jpg', 'transit/klia-ekspres.webp',
     'KLIA Ekspres train in Malaysia',
     'KLIA Ekspres — official transit environment',
     'https://commons.wikimedia.org/wiki/File:KLIA_Ekspres-2015-01-12.jpg',
     'Wikimedia Commons', 'Photo: Bigbug21 / Wikimedia Commons, CC BY-SA 4.0', 'official'),
    ('ktm-train-kl-sentral-ads-2026', '/tmp/official/ktm-train.jpg', 'transit/ktm-train.webp',
     'KTM Komuter train in Kuala Lumpur',
     'KTM Komuter — transit environment',
     'https://commons.wikimedia.org/wiki/File:Class_83_KTM_Komuter_train,_Kuala_Lumpur.jpg',
     'Wikimedia Commons', 'Photo: Two hundred percent / Wikimedia Commons, CC BY-SA 2.5', 'official'),
    ('kl-sentral-station-sponsorship-2024', '/tmp/official/kl-sentral.jpg', 'transit/kl-sentral.webp',
     'Kuala Lumpur Sentral station concourse',
     'KL Sentral — transit environment',
     'https://commons.wikimedia.org/wiki/File:KL_Sentral,_Kuala_Lumpur_20221229_150139.jpg',
     'Wikimedia Commons', 'Photo: Wiki Farazi / Wikimedia Commons, CC0', 'official'),
]


def crop(image):
    ratio = W / H
    width, height = image.size
    if width / height > ratio:
        new_width = round(height * ratio)
        left = (width - new_width) // 2
        return image.crop((left, 0, left + new_width, height))
    new_height = round(width / ratio)
    top = (height - new_height) // 2
    return image.crop((0, top, width, top + new_height))


def main():
    manifest = {}
    for deck, source, rel, alt, caption, source_url, source_label, credit, kind in ASSETS:
        image = Image.open(source).convert('RGB')
        image = crop(image).resize((W, H), Image.LANCZOS)
        target = os.path.join(OUT, rel)
        os.makedirs(os.path.dirname(target), exist_ok=True)
        image.save(target, 'WEBP', quality=86, method=6)
        manifest.setdefault(deck, []).append({
            'path': f'assets/official/{rel}',
            'width': W,
            'height': H,
            'bytes': os.path.getsize(target),
            'alt': alt,
            'caption': caption,
            'source_url': source_url,
            'source_label': source_label,
            'credit': credit,
            'kind': kind,
            'is_illustrative': False,
        })
    json.dump(manifest, open(MANIFEST, 'w', encoding='utf-8'), ensure_ascii=False, indent=2)
    open(MANIFEST, 'a').write('\n')
    print(f'Wrote {sum(len(v) for v in manifest.values())} official images for {len(manifest)} decks')


if __name__ == '__main__':
    main()
