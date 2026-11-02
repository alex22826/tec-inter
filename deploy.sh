#!/usr/bin/env bash
# Выкладка демо на хостинг Beget: архив по SSH (rsync в Git Bash нет).
# После распаковки на сервере каждый PHP-файл проверяется php -l (там та же 5.6).
set -euo pipefail

HOST="alexte1@alexte1.beget.tech"
KEY="$HOME/.ssh/beget_hosting"
DEST="tec-inter26.alexte1.beget.tech/public_html"

cd "$(dirname "$0")"
set +e
tar --exclude=.git --exclude=.gitignore --exclude=.gitattributes --exclude=deploy.sh --exclude=README.md \
    --exclude='storage/leads.log' --exclude=tools --exclude='inc/lang/*.txt' --exclude=.github --exclude=_site --exclude=_site.tgz -czf - . \
  | ssh -i "$KEY" -o BatchMode=yes -o ConnectTimeout=20 "$HOST" \
    "cd $DEST && tar -xzf - && find . -name '*.php' -not -path './cgi-bin/*' | while read f; do php -l \"\$f\" >/dev/null || echo \"ОШИБКА: \$f\"; done" \
  | grep -v 'Welcome to LTD BeGet'
ssh_status=${PIPESTATUS[1]}
set -e

# grep без совпадений тоже даёт ненулевой код, поэтому смотрим именно на ssh
if [ "$ssh_status" -ne 0 ]; then
  echo "НЕ ВЫЛОЖЕНО: ssh завершился с кодом $ssh_status (нет связи с хостингом?)" >&2
  exit 1
fi
echo "Готово: http://tec-inter26.alexte1.beget.tech/"
