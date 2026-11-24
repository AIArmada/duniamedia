#!/usr/bin/env python3
"""Build canonical per-deck content JSON from the catalogue and rate ledger."""

import json
import os
import re
from collections import OrderedDict

import pymupdf

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
CATALOGUE = os.path.join(ROOT, 'resources/data/media-catalogue-2026.json')
LEDGER = os.path.join(ROOT, 'resources/data/rate-list-2026.json')
CHANNELS = os.path.join(ROOT, 'resources/data/rate-card-channels.json')
OUTPUT = os.path.join(ROOT, 'resources/data/rate-cards')


def slugify(value):
    value = value.lower()
    value = re.sub(r'[^a-z0-9]+', '-', value)
    return value.strip('-')


def channel_name(value):
    return re.split(r'\s*/\s*|\s+[—-]\s+', value)[0].strip()


def section_title(value):
    value = re.sub(r'^\s*\d+\s*[|.:-]\s*', '', value)
    return value.replace(' - ', ' — ').strip()


def main():
    catalogue = json.load(open(CATALOGUE, encoding='utf-8'))
    ledger = json.load(open(LEDGER, encoding='utf-8'))
    mappings = json.load(open(CHANNELS, encoding='utf-8'))

    rates_by_slug = {}

    for row in ledger['rates']:
        for slug in filter(None, (part.strip() for part in str(row.get('offering_slugs', '')).split(','))):
            rates_by_slug.setdefault(slug, []).append(row)

    os.makedirs(OUTPUT, exist_ok=True)
    summary = []

    for offering in catalogue['offerings']:
        slug = offering['slug']

        if not offering.get('documents'):
            continue

        owner = offering.get('media_owner_slug') or 'specialist-media'
        mapping = mappings.get(slug, {})
        definitions = mapping.get('channels') or [{
            'slug': f"{owner}-{slugify(channel_name(offering.get('channel_label') or offering['name'])) or slug}",
            'name': channel_name(offering.get('channel_label') or offering['name']),
            'owner': owner,
            'medium': offering.get('medium') or 'Digital',
        }]
        fallback = mapping.get('fallback') or definitions[0]

        channels = OrderedDict()
        for definition in definitions:
            channels[definition['slug']] = {
                'slug': definition['slug'],
                'name': definition['name'],
                'owner': definition.get('owner', owner),
                'medium': definition.get('medium', offering.get('medium')),
                'description': definition.get('description'),
                'audience': definition.get('audience'),
                'match': definition.get('match', []),
                'sections': [],
                'blocks': [],
                'assets': [],
            }

        if fallback['slug'] not in channels:
            channels[fallback['slug']] = {
                'slug': fallback['slug'],
                'name': fallback['name'],
                'owner': fallback.get('owner', owner),
                'medium': fallback.get('medium', offering.get('medium')),
                'description': fallback.get('description'),
                'audience': fallback.get('audience'),
                'match': [],
                'sections': [],
                'blocks': [],
                'assets': [],
            }

        groups = OrderedDict()

        for row in rates_by_slug.get(slug, []):
            text = ' '.join(str(row.get(key) or '') for key in ('section', 'item', 'notes')).lower()
            matched = [
                channel_slug for channel_slug, channel in channels.items()
                if channel['match'] and any(term.lower() in text for term in channel['match'])
            ]

            if not matched:
                matched = [fallback['slug']]

            raw_title = row.get('section') or 'Rates'
            group_key = (raw_title, tuple(sorted(set(matched))))
            groups.setdefault(group_key, []).append(row)

        section_counters = {}

        for (raw_section_title, channel_slugs), rows in groups.items():
            display_title = section_title(raw_section_title)
            base_key = slugify(display_title) or 'rates'
            section_counters[base_key] = section_counters.get(base_key, 0) + 1
            key = base_key if section_counters[base_key] == 1 else f'{base_key}-{section_counters[base_key]}'

            items = []
            for index, row in enumerate(rows):
                unit = row.get('unit') or ''
                items.append({
                    'item': row.get('item') or raw_section_title,
                    'unit': unit or None,
                    'price': row.get('price_rm'),
                    'price_incl_sst': row.get('price_incl_sst_rm'),
                    'price_basis': row.get('price_basis'),
                    'confidence': row.get('confidence'),
                    'notes': row.get('notes') or None,
                    'is_package': 'package' in unit.lower() or 'sponsorship' in unit.lower(),
                    'sort_order': index,
                })

            section = {
                'key': key,
                'title': display_title,
                'intro': None,
                'medium': rows[0].get('medium'),
                'channels': channel_slugs,
                'items': items,
            }

            if len(channel_slugs) == 1:
                channels[channel_slugs[0]]['sections'].append(section)
            else:
                channels.setdefault('_shared', {'sections': []})['sections'].append(section)

        source_pages = 0
        for document in offering.get('documents', []):
            if str(document.get('file', '')).lower().endswith('.pdf'):
                path = os.path.join(ROOT, 'storage/app', document['path'])
                if os.path.isfile(path):
                    source_pages += pymupdf.open(path).page_count

        hero = offering.get('image_path')

        if not hero:
            candidate = f'assets/ratecards/img/{slug}.webp'
            if os.path.isfile(os.path.join(ROOT, 'public', candidate)):
                hero = candidate

        blocks = [{
            'type': 'rich_text',
            'payload': {
                'title': 'Overview',
                'body': offering.get('description') or offering.get('summary') or '',
            },
        }]

        tiers = (offering.get('specifications') or {}).get('price_tiers') or []

        if tiers:
            blocks.append({
                'type': 'feature_list',
                'payload': {
                    'title': 'Rate tiers',
                    'items': [{'title': tier} for tier in tiers],
                },
            })

        if offering.get('specifications', {}).get('formats'):
            blocks.append({
                'type': 'feature_list',
                'payload': {
                    'title': 'Typical formats',
                    'items': [{'title': item} for item in offering['specifications']['formats']],
                },
            })

        channel_payload = []
        for channel in channels.values():
            if 'slug' not in channel or channel['slug'] == '_shared':
                continue

            channel_payload.append({
                'slug': channel['slug'],
                'name': channel['name'],
                'owner': channel['owner'],
                'medium': channel['medium'],
                'description': channel['description'],
                'audience': channel['audience'],
                'hero': hero,
                'stats': [],
                'blocks': channel['blocks'],
                'assets': channel['assets'],
                'sections': channel['sections'],
            })

        shared = channels.get('_shared', {}).get('sections', [])
        total_items = sum(len(section['items']) for channel in channel_payload for section in channel['sections'])
        total_items += sum(len(section['items']) for section in shared)

        payload = {
            'slug': slug,
            'owner': owner,
            'title': offering['name'],
            'summary': offering.get('summary'),
            'description': offering.get('description'),
            'validity': (offering.get('specifications') or {}).get('validity'),
            'terms': offering.get('terms'),
            'hero': hero,
            'medium': offering.get('medium'),
            'source_pages': source_pages,
            'stats': [],
            'blocks': blocks,
            'channels': channel_payload,
            'shared_sections': shared,
        }

        path = os.path.join(OUTPUT, f'{slug}.json')
        with open(path, 'w', encoding='utf-8') as handle:
            json.dump(payload, handle, ensure_ascii=False, indent=2)
            handle.write('\n')

        summary.append((slug, len(channel_payload), total_items, len(shared)))

    print(f'Built {len(summary)} deck files.')
    for slug, channel_count, item_count, shared_count in summary:
        print(f'  {slug:52s} channels={channel_count:2d} items={item_count:4d} shared={shared_count}')


if __name__ == '__main__':
    main()
