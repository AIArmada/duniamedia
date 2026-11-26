#!/usr/bin/env python3
"""Extract meaningful photos from current rate-card PDFs into public galleries."""

import hashlib
import json
import os
import re

import pymupdf
from PIL import Image

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
CATALOGUE = os.path.join(ROOT, 'resources/data/media-catalogue-2026.json')
PDF_DIR = os.path.join(ROOT, 'storage/app/private/ratecards')
OUTPUT_DIR = os.path.join(ROOT, 'public/assets/ratecards/images')
MANIFEST = os.path.join(ROOT, 'resources/data/rate-card-gallery.json')

SUPERSEDED = {
    'klia-t1-airport-2024-premium.pdf',
    'klia-t2-airport-2024.pdf',
}
MAX_WIDTH = 1200
QUALITY = 82

catalogue = json.load(open(CATALOGUE, encoding='utf-8'))
deck_pdfs = {}

for offering in catalogue['offerings']:
    files = [
        os.path.basename(document['file'])
        for document in offering.get('documents', [])
        if document.get('file', '').lower().endswith('.pdf')
    ]
    if files:
        deck_pdfs[offering['slug']] = [name for name in files if name not in SUPERSEDED]


def image_is_usable(data, width, height):
    if width < 600 or width / max(1, height) > 3.2 or height / max(1, width) > 3.2:
        return False

    try:
        image = Image.open(io_bytes(data)).convert('RGBA')
    except Exception:
        return False

    alpha = image.getchannel('A')
    bbox = alpha.getbbox()

    if bbox is None:
        return False

    if alpha.getextrema()[0] < 250:
        transparent = sum(1 for pixel in alpha.getdata() if pixel < 16)
        if transparent / (image.width * image.height) > 0.6:
            return False

    thumb = image.convert('RGB').resize((96, 96))
    pixels = list(thumb.getdata())
    mean = sum(sum(pixel) for pixel in pixels) / (len(pixels) * 3)
    variance = sum((sum(pixel) / 3 - mean) ** 2 for pixel in pixels) / len(pixels)
    white = sum(1 for pixel in pixels if min(pixel) > 235) / len(pixels)
    colorfulness = sum(max(pixel) - min(pixel) for pixel in pixels) / len(pixels)

    if variance < 64:
        return False

    if white > 0.35 and colorfulness < 32:
        return False

    if white > 0.55:
        return False

    return True


def io_bytes(data):
    import io

    return io.BytesIO(data)


def main():
    manifest = {}
    total = 0
    total_bytes = 0

    for deck, pdfs in sorted(deck_pdfs.items()):
        assets = []
        seen = set()
        output = os.path.join(OUTPUT_DIR, deck)
        os.makedirs(output, exist_ok=True)

        for old in os.listdir(output):
            if old.endswith('.webp'):
                os.unlink(os.path.join(output, old))

        for pdf_name in pdfs:
            path = os.path.join(PDF_DIR, pdf_name)

            if not os.path.isfile(path):
                continue

            document = pymupdf.open(path)

            for page_number, page in enumerate(document, start=1):
                page_area = max(1, page.rect.width * page.rect.height)

                for info in page.get_images(full=True):
                    xref, smask, width, height = info[0], info[1], info[2], info[3]

                    if smask:
                        continue

                    try:
                        data = document.extract_image(xref)
                    except Exception:
                        continue

                    if not data or not image_is_usable(data['image'], width, height):
                        continue

                    digest = hashlib.md5(data['image']).hexdigest()

                    if digest in seen:
                        continue

                    drawn = sum(abs(rect) for rect in page.get_image_rects(xref)) if hasattr(page, 'get_image_rects') else 0

                    if drawn / page_area >= 0.6:
                        continue

                    seen.add(digest)
                    image = Image.open(io_bytes(data['image'])).convert('RGB')

                    if image.width > MAX_WIDTH:
                        image = image.resize((MAX_WIDTH, round(image.height * MAX_WIDTH / image.width)), Image.LANCZOS)

                    filename = f'{digest[:10]}.webp'
                    target = os.path.join(output, filename)
                    image.save(target, 'WEBP', quality=QUALITY, method=4)
                    size = os.path.getsize(target)
                    total += 1
                    total_bytes += size

                    assets.append({
                        'path': f'assets/ratecards/images/{deck}/{filename}',
                        'page': page_number,
                        'width': image.width,
                        'height': image.height,
                        'bytes': size,
                        'alt': f'{deck.replace("-", " ")} visual from page {page_number}',
                        'caption': f'Source deck, page {page_number}',
                        'kind': 'photo',
                    })

        assets.sort(key=lambda item: (-item['width'] * item['height'], item['page']))
        manifest[deck] = assets

    with open(MANIFEST, 'w', encoding='utf-8') as handle:
        json.dump(manifest, handle, ensure_ascii=False, indent=2)
        handle.write('\n')

    print(f'Extracted {total} images ({total_bytes / 1024 / 1024:.1f} MB) for {len(manifest)} decks.')
    for deck, assets in manifest.items():
        print(f'  {deck:52s} {len(assets):2d} images')


if __name__ == '__main__':
    main()
