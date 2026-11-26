#!/usr/bin/env python3
"""Install a generated image into a deck gallery and update the manifest."""

import json
import os
import sys

from PIL import Image

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
MANIFEST = os.path.join(ROOT, 'resources/data/rate-card-generated-gallery.json')
OUTPUT = os.path.join(ROOT, 'public/assets/ratecards/images')
WIDTH, HEIGHT = 1200, 800


def main():
    source, deck, index = sys.argv[1], sys.argv[2], int(sys.argv[3])
    alt = sys.argv[4] if len(sys.argv) > 4 else f'Generated editorial visual for {deck}'
    caption = sys.argv[5] if len(sys.argv) > 5 else 'Dunia Media editorial visual'

    image = Image.open(source).convert('RGB')
    ratio = WIDTH / HEIGHT
    width, height = image.size

    if width / height > ratio:
        new_width = round(height * ratio)
        left = (width - new_width) // 2
        image = image.crop((left, 0, left + new_width, height))
    else:
        new_height = round(width / ratio)
        top = (height - new_height) // 2
        image = image.crop((0, top, width, top + new_height))

    image = image.resize((WIDTH, HEIGHT), Image.LANCZOS)
    directory = os.path.join(OUTPUT, deck)
    os.makedirs(directory, exist_ok=True)
    filename = f'generated-{index}.webp'
    target = os.path.join(directory, filename)
    image.save(target, 'WEBP', quality=84, method=6)
    path = f'assets/ratecards/images/{deck}/{filename}'

    manifest = {}
    if os.path.isfile(MANIFEST):
        manifest = json.load(open(MANIFEST, encoding='utf-8'))

    assets = [asset for asset in manifest.get(deck, []) if asset.get('path') != path]
    assets.append({
        'path': path,
        'page': None,
        'width': WIDTH,
        'height': HEIGHT,
        'bytes': os.path.getsize(target),
        'alt': alt,
        'caption': caption,
        'kind': 'generated',
    })
    manifest[deck] = assets
    json.dump(manifest, open(MANIFEST, 'w', encoding='utf-8'), ensure_ascii=False, indent=2)
    open(MANIFEST, 'a').write('\n')
    print(path)


if __name__ == '__main__':
    main()
