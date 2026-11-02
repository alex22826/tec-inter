#!/usr/bin/env bash
# Статическая копия сайта для GitHub Pages: рендерит все страницы PHP в папку $1 (по умолчанию _site).
# Запускается в GitHub Actions (.github/workflows/pages.yml), подойдёт и любой машине с PHP 5.6+.
set -euo pipefail

OUT="${1:-_site}"
ERRLOG="$(mktemp)"
rm -rf "$OUT"
mkdir -p "$OUT"

# предупреждения PHP идут в stderr → в лог, а не в HTML; непустой лог = ошибка сборки
render() {
  php -d display_errors=stderr -d error_reporting=-1 tools/render.php "$1" 2>>"$ERRLOG"
}

while read -r u; do
  mkdir -p "$OUT$u"
  render "$u" > "$OUT${u}index.html"
done < <(php tools/pages-list.php)

render /404-not-found/ > "$OUT/404.html"
render /sitemap.xml > "$OUT/sitemap.xml"
cp -r assets "$OUT/assets"
touch "$OUT/.nojekyll"   # Pages отдаёт файлы как есть, без Jekyll

echo "Страниц: $(find "$OUT" -name '*.html' | wc -l)"
if [ -s "$ERRLOG" ]; then
  echo "Сообщения PHP при рендере:"
  sort -u "$ERRLOG"
  exit 1
fi
