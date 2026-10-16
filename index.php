<?php
// Единая точка входа: .htaccess отправляет сюда все адреса, здесь выбираем страницу.

$CFG = require __DIR__ . '/inc/config.php';
require __DIR__ . '/inc/data.php';
require __DIR__ . '/inc/helpers.php';

$uri  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim(rawurldecode($uri), '/');

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
    redirect301(url($OLD[$path]));
}

if ($path === 'sitemap.xml') {
    header('Content-Type: application/xml; charset=utf-8');
    $base = $CFG['site_url'];
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach ($ROUTES as $slug => $p) {
        echo '  <url><loc>' . e($base . url($slug ? $slug . '/' : '')) . '</loc></url>' . "\n";
    }
    foreach ($POSTS as $slug => $post) {
        echo '  <url><loc>' . e($base . url('news/' . $slug . '/')) . '</loc><lastmod>' . e($post['date']) . '</lastmod></url>' . "\n";
    }
    echo '</urlset>';
    exit;
}

$page = '404';
$SLUG = '';
if (isset($ROUTES[$path])) {
    // единый вид адресов — со слешем на конце
    if ($path !== '' && substr($uri, -1) !== '/') {
        redirect301(url($path . '/'));
    }
    $page = $ROUTES[$path];
} elseif (preg_match('#^news/([a-z0-9-]+)$#', $path, $m) && isset($POSTS[$m[1]])) {
    if (substr($uri, -1) !== '/') {
        redirect301(url($path . '/'));
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

require __DIR__ . '/pages/' . $page . '.php';
