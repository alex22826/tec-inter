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

require dirname(__DIR__) . '/index.php';
