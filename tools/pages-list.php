<?php
// Список адресов для статической копии: все разделы и статьи на всех языках.
// Печатает по строке на адрес, без префикса /tec-inter (его добавляет сам сайт в ссылках).

if (PHP_SAPI !== 'cli') {
    exit(1);
}

$CFG = require dirname(__DIR__) . '/inc/config.php';
require dirname(__DIR__) . '/inc/data.php';
require dirname(__DIR__) . '/inc/i18n.php';

$paths = array('', 'solutions/', 'projects/', 'partners/', 'about/', 'news/', 'contacts/');
foreach ($POSTS as $slug => $post) {
    $paths[] = 'news/' . $slug . '/';
}
foreach ($LANGS as $code => $L) {
    foreach ($paths as $p) {
        echo url_lang($code, $p), "\n";
    }
}
