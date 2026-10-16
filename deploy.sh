#!/usr/bin/env bash
# Выкладка демо на хостинг Beget: архив по SSH (rsync в Git Bash нет).
# После распаковки на сервере каждый PHP-файл проверяется php -l (там та же 5.6).
set -euo pipefail

HOST="alexte1@alexte1.beget.tech"
KEY="$HOME/.ssh/beget_hosting"
DEST="tec-inter26.alexte1.beget.tech/public_html"

cd "$(dirname "$0")"
tar --exclude=.git --exclude=.gitignore --exclude=.gitattributes --exclude=deploy.sh --exclude=README.md \
    --exclude='storage/leads.log' -czf - . \
  | ssh -i "$KEY" -o BatchMode=yes "$HOST" \
    "cd $DEST && tar -xzf - && find . -name '*.php' -not -path './cgi-bin/*' | while read f; do php -l \"\$f\" >/dev/null || echo \"ОШИБКА: \$f\"; done" \
  | grep -v 'Welcome to LTD BeGet' || true

echo "Готово: http://tec-inter26.alexte1.beget.tech/"
