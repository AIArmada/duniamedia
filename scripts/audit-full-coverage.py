#!/usr/bin/env python3
"""Full page, table, price and image audit for every rate-card PDF."""

import hashlib
import json
import os
import re
import sqlite3
from concurrent.futures import ProcessPoolExecutor, as_completed

import pymupdf

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB = os.path.join(ROOT, 'database/database.sqlite')
LEDGER = os.path.join(ROOT, 'resources/data/rate-list-2026.json')
PDF_DIR = os.path.join(ROOT, 'storage/app/private/ratecards')
OUTPUT = os.path.join(ROOT, 'storage/app/private/qa/full-audit.json')

ledger = json.load(open(LEDGER, encoding='utf-8'))
OFFERINGS = {}
for row in ledger['rates']:
    for slug in str(row.get('offering_slugs') or '').split(','):
        OFFERINGS.setdefault(row['source_file'], set()).add(slug.strip())

NUMBER = r'(?<![\d,.])(\d{1,3}(?:,\d{3})+(?:\.\d+)?|\d+\.\d{2})(?![\d,])'
PRICE_HEADER = re.compile(r'price|rate|cost|investment|rental|production|lighting|total|sst|charge|rm', re.I)
METRIC = re.compile(
    r'impression|view|follower|reach|reader|visitor|audience|subscriber|email|circulation|'
    r'unique|pageview|download|install|sales|unit|outlet|traffic|population|household|'
    r'grp|rating|share|rank|size|dimension|duration|second|minute|hour|day|week|month|year|'
    r'phone|whatsapp|tel|fax|postcode|address|invoice|quotation|quote|date|deadline|'
    r'campaign|slot|spot|episode|insertion|panel|screen|width|height|pixel|px|cm|mm|ft|kg',
    re.I,
)
TIME = re.compile(r'\b\d{1,2}[.:]\d{2}\s*(am|pm)\b', re.I)


def normalize(token):
    return float(token.replace(',', ''))


def valid(value, context):
    if 2018 <= value <= 2030 and value == int(value):
        return False
    if value < 1:
        return False
    if value < 100 and TIME.search(context):
        return False
    return True


def page_numbers(page):
    found = []
    text = page.get_text('text')

    for line in text.splitlines():
        line = re.sub(r'\s+', ' ', line).strip()
        if not line:
            continue

        for match in re.finditer(r'RM\s?' + NUMBER, line, re.I):
            value = normalize(match.group(1))
            if valid(value, line):
                found.append((value, line[:220]))

        if METRIC.search(line) or TIME.search(line):
            continue

        if re.search(r'price|rate|cost|investment|rental|production|lighting|total|sst|charge', line, re.I):
            for match in re.finditer(NUMBER, line):
                value = normalize(match.group(1))
                if valid(value, line):
                    found.append((value, line[:220]))

    try:
        tables = page.find_tables()
    except Exception:
        tables = []

    for table in tables:
        try:
            rows = table.extract()
        except Exception:
            continue
        if not rows:
            continue
        header = [str(cell or '') for cell in rows[0]]
        price_columns = {i for i, cell in enumerate(header) if PRICE_HEADER.search(cell)}
        for row in rows[1:]:
            for index, cell in enumerate(row):
                if index not in price_columns or cell is None:
                    continue
                text_cell = str(cell)
                for match in re.finditer(NUMBER, text_cell):
                    value = normalize(match.group(1))
                    if valid(value, text_cell):
                        found.append((value, ' | '.join(str(c or '') for c in row)[:220]))

    table_count = len(getattr(tables, 'tables', []) or [])

    return found, table_count


def image_inventory(page):
    images = []
    page_area = max(1, page.rect.width * page.rect.height)

    for info in page.get_images(full=True):
        xref = info[0]
        width = info[2]
        height = info[3]
        try:
            data = page.parent.extract_image(xref)['image']
        except Exception:
            data = b''
        digest = hashlib.md5(data).hexdigest() if data else None
        area_ratio = (width * height) / page_area if page_area else 0
        images.append({
            'xref': xref,
            'width': width,
            'height': height,
            'bytes': len(data),
            'md5': digest,
            'large': width >= 600 and height >= 400,
            'full_page': area_ratio >= 0.6,
        })

    return images


def db_prices(pdf_name):
    conn = sqlite3.connect(DB)
    conn.row_factory = sqlite3.Row
    values = set()
    for slug in OFFERINGS.get(pdf_name, set()):
        for row in conn.execute(
            'select price_rm, price_incl_sst_rm, notes, source_text from rate_card_entries where source_file=?',
            (slug,),
        ):
            for field in row:
                if field is None:
                    continue
                text = str(field)
                for token in re.findall(NUMBER, text):
                    values.add(normalize(token))
                for token in re.findall(r'\d+(?:\.\d+)?', text):
                    values.add(float(token))
    conn.close()
    return values


def audit_pdf(pdf_name):
    path = os.path.join(PDF_DIR, pdf_name)
    doc = pymupdf.open(path)
    known = db_prices(pdf_name)
    pages = []
    missing = []
    table_pages = 0
    images_total = 0
    large_images = 0
    full_page_images = 0

    for number, page in enumerate(doc, start=1):
        try:
            values, table_count = page_numbers(page)
        except Exception as error:
            values, table_count = [], 0
        images = image_inventory(page)
        if table_count:
            table_pages += 1
        images_total += len(images)
        large_images += sum(1 for image in images if image['large'])
        full_page_images += sum(1 for image in images if image['full_page'])

        page_missing = [
            {'value': value, 'context': context}
            for value, context in values
            if value not in known
        ]
        missing.extend({'page': number, **item} for item in page_missing)

        pages.append({
            'page': number,
            'chars': len(page.get_text('text')),
            'tables': table_count,
            'price_values': len(values),
            'missing_values': len(page_missing),
            'images': len(images),
            'large_images': sum(1 for image in images if image['large']),
            'full_page_images': sum(1 for image in images if image['full_page']),
            'image_hashes': [image['md5'] for image in images if image['md5']],
        })

    return {
        'pdf': pdf_name,
        'pages': len(pages),
        'table_pages': table_pages,
        'images': images_total,
        'large_images': large_images,
        'full_page_images': full_page_images,
        'missing': missing,
        'page_detail': pages,
    }


def main():
    pdfs = sorted(name for name in os.listdir(PDF_DIR) if name.endswith('.pdf'))
    results = []

    with ProcessPoolExecutor(max_workers=min(8, os.cpu_count() or 4)) as executor:
        futures = {executor.submit(audit_pdf, name): name for name in pdfs}
        for future in as_completed(futures):
            name = futures[future]
            try:
                results.append(future.result())
            except Exception as error:
                results.append({'pdf': name, 'error': str(error), 'pages': 0, 'missing': []})
            print(f'done {name}', flush=True)

    results.sort(key=lambda item: item['pdf'])
    os.makedirs(os.path.dirname(OUTPUT), exist_ok=True)
    with open(OUTPUT, 'w', encoding='utf-8') as handle:
        json.dump(results, handle, ensure_ascii=False, indent=2)

    print(f'\nWrote {OUTPUT}')
    print(f"{'PDF':55s} pages tables images large full missing")
    for result in results:
        print(
            f"{result['pdf']:55s} {result.get('pages', 0):5d} {result.get('table_pages', 0):6d} "
            f"{result.get('images', 0):6d} {result.get('large_images', 0):5d} "
            f"{result.get('full_page_images', 0):4d} {len(result.get('missing', [])):7d}"
        )


if __name__ == '__main__':
    main()
