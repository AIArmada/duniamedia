#!/usr/bin/env python3
"""Normalise a generated hero to the rate-card 3:2 WebP set."""

import os
import sys

from PIL import Image

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
OUTPUT = os.path.join(ROOT, 'public/assets/ratecards/img')
WIDTH, HEIGHT = 1536, 1024


def main():
    source, slug = sys.argv[1], sys.argv[2]
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
    os.makedirs(OUTPUT, exist_ok=True)

    paths = []
    for target_width in (WIDTH, 960, 480):
        variant = image.resize((target_width, round(HEIGHT * target_width / WIDTH)), Image.LANCZOS)
        suffix = '' if target_width == WIDTH else f'-{target_width}'
        path = os.path.join(OUTPUT, f'{slug}{suffix}.webp')
        variant.save(path, 'WEBP', quality=82, method=6)
        paths.append(path)

    print('\n'.join(paths))


if __name__ == '__main__':
    main()
