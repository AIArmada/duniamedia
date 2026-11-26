#!/usr/bin/env python3
"""Find price values in each PDF that do not exist in its database rows.

Uses PDF tables plus RM-prefixed text. Partial comma fragments are ignored.
"""

import json
import os
import re
import sqlite3

import pymupdf

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB = os.path.join(ROOT, 'database/database.sqlite')
LEDGER = os.path.join(ROOT, 'resources/data/rate-list-2026.json')
PDF_DIR = os.path.join(ROOT, 'storage/app/private/ratecards')

ledger = json.load(open(LEDGER, encoding='utf-8'))
offering_for_source = {}
for row in ledger['rates']:
    for slug in str(row.get('offering_slugs') or '').split(','):
        offering_for_source.setdefault(row['source_file'], set()).add(slug.strip())

conn = sqlite3.connect(DB)
conn.row_factory = sqlite3.Row

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


def is_valid(value, context):
    if 2018 <= value <= 2030 and value == int(value):
        return False
    if value < 1:
        return False
    if value < 100 and TIME.search(context):
        return False
    return True


def page_values(page):
    found = []
    text = page.get_text('text')

    if not re.search(r'RM|price|rate|cost|investment|rental|production|lighting|total|charge', text, re.I):
        return found

    for line in text.splitlines():
        line = re.sub(r'\s+', ' ', line).strip()
        if not line:
            continue

        for match in re.finditer(r'RM\s?' + NUMBER, line, re.I):
            value = normalize(match.group(1))
            if is_valid(value, line):
                found.append((value, line[:220]))

        if METRIC.search(line) or TIME.search(line):
            continue

        if re.search(r'price|rate|cost|investment|rental|production|lighting|total|sst|charge', line, re.I):
            for match in re.finditer(NUMBER, line):
                value = normalize(match.group(1))
                if is_valid(value, line):
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
                    if is_valid(value, text_cell):
                        found.append((value, ' | '.join(str(c or '') for c in row)[:220]))

    return found


def db_prices(pdf_name):
    values = set()
    for slug in offering_for_source.get(pdf_name, set()):
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
    return values


SUPERSEDED = {
    'klia-t1-airport-2024-premium.pdf',
    'klia-t2-airport-2024.pdf',
}

for pdf_name in sorted(os.listdir(PDF_DIR)):
    if not pdf_name.endswith('.pdf') or pdf_name in SUPERSEDED:
        continue
    known = db_prices(pdf_name)
    missing = []
    doc = pymupdf.open(os.path.join(PDF_DIR, pdf_name))

    for number, page in enumerate(doc, start=1):
        for value, context in page_values(page):
            if value not in known:
                missing.append((number, value, context))

    if missing:
        print(f'\n{pdf_name}: {len(missing)} unmatched price-like values')
        seen = set()
        for page, value, context in missing:
            key = (page, value)
            if key in seen:
                continue
            seen.add(key)
            print(f'  p{page} {value:>14,.2f}  {context}')
