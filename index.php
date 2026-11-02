<?php
// Единая точка входа: .htaccess отправляет сюда все адреса, здесь выбираем язык и страницу.

$CFG = require __DIR__ . '/inc/config.php';
if (defined('TEC_STATIC')) {
    // сборка статической копии для GitHub Pages (tools/render.php)
    $CFG['static']    = true;
    $CFG['site_url']  = $CFG['pages_origin'];
    $CFG['base_path'] = $CFG['pages_base'];
}
require __DIR__ . '/inc/data.php';
require __DIR__ . '/inc/i18n.php';
require __DIR__ . '/inc/helpers.php';

$uri  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim(rawurldecode($uri), '/');
$qs   = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] !== '' ? '?' . $_SERVER['QUERY_STRING'] : '';

$ROUTES = array(
    ''          => 'home',
    'solutions' => 'solutions',
    'projects'  => 'projects',
    'partners'  => 'partners',
    'about'     => 'about',
    'news'      => 'news',
    'contacts'  => 'contacts',
);

// 301 со старых адресов WordPress-сайта (ТЗ: «301-редиректы со старых адресов»).
// Остальные старые адреса (/solutions/, /about/, /partners/, /contacts/) совпадают с новыми.
$OLD = array(
    'proekty' => 'projects/',
);

function redirect301($to)
{
    header('Location: ' . $to, true, 301);
    exit;
}

if (isset($OLD[$path])) {
    redirect301(url_lang('ru', $OLD[$path]));
}

// Язык — первый сегмент адреса: /kz/..., /en/...; без префикса — русский
$seg  = explode('/', $path, 2);
$rest = isset($seg[1]) ? $seg[1] : '';
if ($seg[0] === 'ru') {
    redirect301(url_lang('ru', $rest !== '' ? $rest . '/' : '') . $qs); // русский — без префикса
}
$lang = 'ru';
foreach ($LANGS as $code => $L) {
    if ($L['prefix'] !== '' && $seg[0] === $L['prefix']) {
        $lang = $code;
        $path = $rest;
    }
}
set_lang($lang);

if ($path === 'sitemap.xml' && $lang === 'ru') {
    header('Content-Type: application/xml; charset=utf-8');
    $base = $CFG['site_url'];
    $paths = array();
    foreach ($ROUTES as $slug => $p) {
        $paths[] = $slug ? $slug . '/' : '';
    }
    foreach ($POSTS as $slug => $post) {
        $paths[] = 'news/' . $slug . '/';
    }
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";
    foreach ($paths as $p) {
        foreach ($LANGS as $code => $L) {
            echo '  <url><loc>' . e($base . url_lang($code, $p)) . '</loc>';
            foreach ($LANGS as $c2 => $L2) {
                echo '<xhtml:link rel="alternate" hreflang="' . $L2['html'] . '" href="' . e($base . url_lang($c2, $p)) . '"/>';
            }
            echo "</url>\n";
        }
    }
    echo '</urlset>';
    exit;
}

$page = '404';
$SLUG = '';
$PAGE_PATH = ''; // путь страницы без языка — для переключателя языков и hreflang
if (isset($ROUTES[$path])) {
    $PAGE_PATH = $path !== '' ? $path . '/' : '';
    // единый вид адресов — со слешем на конце (/en → /en/, /solutions → /solutions/)
    if ($uri !== '/' && substr($uri, -1) !== '/') {
        redirect301(url_lang($lang, $PAGE_PATH) . $qs);
    }
    $page = $ROUTES[$path];
} elseif (preg_match('#^news/([a-z0-9-]+)$#', $path, $m) && isset($POSTS[$m[1]])) {
    $PAGE_PATH = 'news/' . $m[1] . '/';
    if (substr($uri, -1) !== '/') {
        redirect301(url_lang($lang, $PAGE_PATH) . $qs);
    }
    $page = 'article';
    $SLUG = $m[1];
}

if ($page === '404') {
    http_response_code(404);
}
if ($CFG['demo']) {
    header('X-Robots-Tag: noindex, nofollow');
}
header('Content-Language: ' . $LANGS[$lang]['html']);

require __DIR__ . '/pages/' . $page . '.php';
