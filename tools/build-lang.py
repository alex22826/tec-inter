"""Собирает inc/lang/en.php и inc/lang/kz.php из таблиц inc/lang/ui-*.txt.

Формат строки таблицы: «русский ⟫ английский ⟫ казахский». Строки с # — комментарии.
Запуск из корня проекта:  python tools/build-lang.py [keys.json]
keys.json — список строк без перевода, снятый с сайта (?i18n_missing=1): тогда
скрипт покажет, каких переводов не хватает.
"""
import glob
import json
import os
import sys

sys.stdout.reconfigure(encoding="utf-8")
ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
LANG_DIR = os.path.join(ROOT, "inc", "lang")
SEP = " ⟫ "
LANGS = ["en", "kz"]


def php_str(s):
    return "'" + s.replace("\\", "\\\\").replace("'", "\\'") + "'"


def main():
    table = {}  # русский -> [en, kz]
    problems = 0
    for path in sorted(glob.glob(os.path.join(LANG_DIR, "ui-*.txt"))):
        for n, line in enumerate(open(path, encoding="utf-8"), 1):
            line = line.rstrip("\n")
            if not line.strip() or line.lstrip().startswith("#"):
                continue
            parts = line.split(SEP)
            where = f"{os.path.basename(path)}:{n}"
            if len(parts) != 1 + len(LANGS) or not all(p.strip() for p in parts):
                print(f"ОШИБКА {where}: нужно {1 + len(LANGS)} колонки через «{SEP.strip()}»")
                problems += 1
                continue
            ru, *tr = [p.strip() for p in parts]
            if ru in table and table[ru] != tr:
                print(f"ОШИБКА {where}: «{ru}» уже переведено иначе: {table[ru]}")
                problems += 1
            table[ru] = tr

    if len(sys.argv) > 1:
        keys = json.load(open(sys.argv[1], encoding="utf-8"))
        missing = [k for k in keys if k not in table]
        for k in missing:
            print(f"НЕТ ПЕРЕВОДА: {k}")
        problems += len(missing)

    for i, lang in enumerate(LANGS):
        rows = "\n".join(f"        {php_str(ru)} => {php_str(tr[i])}," for ru, tr in table.items())
        php = (
            "<?php\n"
            "// Сгенерировано tools/build-lang.py из inc/lang/ui-*.txt — правьте таблицы и пересоберите.\n"
            "return array(\n"
            "    'ui' => array(\n"
            f"{rows}\n"
            "    ),\n"
            f"    'posts' => require __DIR__ . '/posts-{lang}.php',\n"
            ");\n"
        )
        with open(os.path.join(LANG_DIR, f"{lang}.php"), "w", encoding="utf-8", newline="\n") as f:
            f.write(php)
    print(f"Строк в словаре: {len(table)}; проблем: {problems}")
    sys.exit(1 if problems else 0)


if __name__ == "__main__":
    main()
