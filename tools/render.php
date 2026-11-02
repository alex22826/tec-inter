<?php
// Рендер одной страницы в HTML для статической копии на GitHub Pages.
// Запуск: php tools/render.php /kz/about/ > out/kz/about/index.html
// Каждая страница — отдельный процесс PHP: index.php объявляет функции и не может
// подключаться дважды.

if (PHP_SAPI !== 'cli') {
    exit(1);
}

// в CLI-настройках хостинга часового пояса нет — без этого PHP 5.6 пишет Warning прямо в HTML
date_default_timezone_set('Asia/Almaty');
define('TEC_STATIC', true);
$_SERVER['REQUEST_URI'] = isset($argv[1]) ? $argv[1] : '/';
$_SERVER['QUERY_STRING'] = '';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['HTTP_HOST'] = 'alex22826.github.io';

// Строки без перевода на KZ/EN — в stderr: build-static.sh валит сборку на любой вывод туда,
// так что правка русского текста без правки ключа в inc/lang/ui-*.txt не пройдёт незамеченной.
register_shutdown_function(function () {
    global $LANG, $I18N_MISSING;
    if ($LANG === 'ru' || empty($I18N_MISSING)) {
        return;
    }
    foreach (array_keys($I18N_MISSING) as $ru) {
        fwrite(STDERR, 'Нет перевода [' . $LANG . ']: ' . $ru . "\n");
    }
});

require dirname(__DIR__) . '/index.php';
