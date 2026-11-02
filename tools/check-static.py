"""Проверка статической копии перед публикацией: все внутренние ссылки ведут на
существующие файлы и начинаются с префикса Pages (/tec-inter/).

Запуск: python tools/check-static.py _site /tec-inter
"""
import os
import re
import sys
from urllib.parse import urlsplit

sys.stdout.reconfigure(encoding="utf-8")
root, base = sys.argv[1], sys.argv[2].rstrip("/")
attr = re.compile(r'(?:href|src|action)="([^"#]*)', re.I)
bad = 0
checked = 0

for dirpath, _, files in os.walk(root):
    for name in files:
        if not name.endswith(".html"):
            continue
        page = os.path.join(dirpath, name)
        rel_page = os.path.relpath(page, root)
        for link in attr.findall(open(page, encoding="utf-8").read()):
            if not link.startswith("/") or link.startswith("//"):
                continue  # внешние ссылки, tel:, mailto: и якоря
            path = urlsplit(link).path
            if path == base + "/api/lead.php":
                continue  # форма: на Pages не отправляется (data-static)
            if not path.startswith(base + "/"):
                print(f"БЕЗ ПРЕФИКСА {base}: {link}  ({rel_page})")
                bad += 1
                continue
            target = os.path.join(root, path[len(base) + 1:])
            if path.endswith("/"):
                target = os.path.join(target, "index.html")
            checked += 1
            if not os.path.isfile(target):
                print(f"НЕТ ФАЙЛА: {link}  ({rel_page})")
                bad += 1

print(f"Проверено ссылок: {checked}, проблем: {bad}")
sys.exit(1 if bad else 0)
