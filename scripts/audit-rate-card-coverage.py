#!/usr/bin/env python3
"""Audit PDF page/rate coverage against the database."""

import json
import os
import re
import sqlite3

import pymupdf

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB = os.path.join(ROOT, 'database/database.sqlite')
CATALOGUE = os.path.join(ROOT, 'resources/data/media-catalogue-2026.json')
LEDGER = os.path.join(ROOT, 'resources/data/rate-list-2026.json')
PDF_DIR = os.path.join(ROOT, 'storage/app/private/ratecards')

# deck slug -> source pdf filenames
catalogue = json.load(open(CATALOGUE, encoding='utf-8'))
deck_pdfs = {}
for offering in catalogue['offerings']:
    files = [os.path.basename(d['file']) for d in offering.get('documents', []) if d['file'].lower().endswith('.pdf')]
    if files:
        deck_pdfs[offering['slug']] = files

conn = sqlite3.connect(DB)
conn.row_factory = sqlite3.Row

def rows_for(source_name):
    # entries source_file is the deck slug; map from the rate ledger
    slugs = {r['offering_slugs'] for r in ledger['rates'] if r['source_file'] == source_name}
    slugs = {s for row in slugs for s in str(row).split(',') if s}
    out = []
    for slug in slugs:
        out += conn.execute(
            'select product_id, page, item, price_rm, source_text from rate_card_entries where source_file=?',
            (slug,),
        ).fetchall()
    return out

ledger = json.load(open(LEDGER, encoding='utf-8'))
pdfs = sorted({f for files in deck_pdfs.values() for f in files})
report = []
total_pages = 0
total_rm = 0
total_numbers = 0
total_rows = 0

for pdf_name in pdfs:
    path = os.path.join(PDF_DIR, pdf_name)
    if not os.path.isfile(path):
        report.append({'pdf': pdf_name, 'missing': True})
        continue

    doc = pymupdf.open(path)
    db_rows = rows_for(pdf_name)

    for number, page in enumerate(doc, start=1):
        total_pages += 1
        text = page.get_text('text')
        rm = len(re.findall(r'RM\s?\d', text, re.I))
        numbers = re.findall(r'(?<![\w.])\d{1,3}(?:,\d{3})+(?:\.\d+)?', text)
        page_rows = [r for r in db_rows if int(r['page'] or 0) == number]
        priced = [r for r in page_rows if r['price_rm'] is not None]
        total_rm += rm
        total_numbers += len(numbers)
        total_rows += len(page_rows)
        flags = []
        if rm > 0 and not page_rows:
            flags.append('RM_TEXT_NO_ROWS')
        if len(numbers) >= 3 and not page_rows:
            flags.append('NUMBERS_NO_ROWS')
        if len(numbers) > max(4, len(priced) * 3) and priced:
            flags.append('MORE_NUMBERS_THAN_ROWS')
        if not text.strip() and not page_rows:
            flags.append('IMAGE_ONLY_NO_ROWS')
        if flags:
            report.append({
                'pdf': pdf_name,
                'page': number,
                'rm': rm,
                'numbers': len(numbers),
                'rows': len(page_rows),
                'priced': len(priced),
                'flags': flags,
                'excerpt': re.sub(r'\s+', ' ', text)[:180],
            })

print(f'PDFs={len(pdfs)} pages={total_pages} RM markers={total_rm} comma-number tokens={total_numbers} DB rows={total_rows}')
print(f'flagged pages={len(report)}')
for item in report:
    if item.get('missing'):
        print(f"MISSING PDF {item['pdf']}")
        continue
    print(f"{item['pdf']:55s} p{item['page']:<3} rm={item['rm']:<3} nums={item['numbers']:<3} rows={item['rows']:<3} priced={item['priced']:<3} {','.join(item['flags'])}")
    if item['excerpt']:
        print(f"    {item['excerpt']}")

print('\nGAPS:')
for gap in ledger['gaps']:
    print(f"  {gap['source_file']}: {gap['gap'] if isinstance(gap['gap'], str) else gap['gap'].get('note', gap['gap'])}")
print('\nMISSING DOCUMENTS:')
print(json.dumps(ledger['missing_documents'], ensure_ascii=False, indent=2))
