#!/usr/bin/env python3
"""Use authentic source-deck photography for heroes that still had generated scenes."""

import json
import os
from PIL import Image

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
OUT = os.path.join(ROOT, 'public/assets/ratecards/img')
SOURCES = os.path.join(ROOT, 'resources/data/official-media-sources.json')
W, H = 1536, 1024
SLUGS = [
    'bfm-2026-business-radio',
    'cubig-klcc-junction-2026-promo',
    'karnival-jom-heboh-2026-venue',
    'klcc-snorkels-2024-big-tree',
    'klia-t1-airport-2024-premium',
    'klia-t2-airport-2024',
    'konsert-kita-suara-anti-buli-2026',
    'lamppost-digital-bunting-kl-2026',
    'mobile-truck-activation-life-events',
    'podcast-utama-buletin-tv3',
    'wowshop-airtime-2025',
]


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


def main():
    gallery = json.load(open(os.path.join(ROOT, 'resources/data/rate-card-gallery.json'), encoding='utf-8'))
    sources = json.load(open(SOURCES, encoding='utf-8')) if os.path.isfile(SOURCES) else {}
    for slug in SLUGS:
        assets = [a for a in gallery.get(slug, []) if os.path.isfile(os.path.join(ROOT, 'public', a['path']))]
        if not assets:
            print('skip', slug)
            continue
        landscape = [a for a in assets if (a.get('width') or 0) >= (a.get('height') or 0)]
        asset = (landscape or assets)[0]
        image = Image.open(os.path.join(ROOT, 'public', asset['path'])).convert('RGB')
        save(crop_cover(image), slug)
        sources[slug] = {
            'source_url': None,
            'source_label': 'Source deck',
            'credit': 'Source-deck photography',
            'kind': 'source',
        }
        print('source hero', slug, asset['path'])
    json.dump(sources, open(SOURCES, 'w', encoding='utf-8'), ensure_ascii=False, indent=2)
    open(SOURCES, 'a').write('\n')


if __name__ == '__main__':
    main()
