<?php
// Языки сайта. RU — основной, без префикса в адресе; KZ и EN — /kz/... и /en/...
// Переводы — в inc/lang/<код>.php: 'ui' — строки «русский текст => перевод»,
// 'posts' — тексты статей целиком (по slug).

$LANGS = array(
    'ru' => array('label' => 'RU', 'html' => 'ru', 'locale' => 'ru_RU', 'prefix' => ''),
    'kz' => array('label' => 'KZ', 'html' => 'kk', 'locale' => 'kk_KZ', 'prefix' => 'kz'),
    'en' => array('label' => 'EN', 'html' => 'en', 'locale' => 'en_US', 'prefix' => 'en'),
);
$LANG = 'ru';
$I18N = array('ui' => array(), 'posts' => array());
$I18N_MISSING = array(); // строки без перевода — для проверки (?i18n_missing=1)

function set_lang($code)
{
    global $LANG, $LANGS, $I18N;
    $LANG = isset($LANGS[$code]) ? $code : 'ru';
    $I18N = array('ui' => array(), 'posts' => array());
    if ($LANG !== 'ru') {
        $I18N = array_merge($I18N, require __DIR__ . '/lang/' . $LANG . '.php');
    }
}

// Перевод строки. Нет перевода — показываем русский текст и запоминаем пропуск.
function t($ru)
{
    global $LANG, $I18N, $I18N_MISSING;
    // без кириллицы (Cisco, 120+, 2008) переводить нечего
    if ($LANG === 'ru' || !preg_match('/[А-Яа-яЁё]/u', $ru)) {
        return $ru;
    }
    if (isset($I18N['ui'][$ru])) {
        return $I18N['ui'][$ru];
    }
    $I18N_MISSING[$ru] = true;
    return $ru;
}

// Подпись в главном меню: можно дать короткий вариант ключом «меню: …»
// (казахские названия разделов длиннее и не влезают в шапку)
function t_menu($ru)
{
    global $LANG, $I18N;
    if ($LANG !== 'ru' && isset($I18N['ui']['меню: ' . $ru])) {
        return $I18N['ui']['меню: ' . $ru];
    }
    return t($ru);
}

// Перевод текста статьи (HTML) по slug
function t_post($slug, $field, $ru)
{
    global $LANG, $I18N, $I18N_MISSING;
    if ($LANG === 'ru') {
        return $ru;
    }
    if (isset($I18N['posts'][$slug][$field])) {
        return $I18N['posts'][$slug][$field];
    }
    $I18N_MISSING['post:' . $slug . ':' . $field] = true;
    return $ru;
}

// Адрес страницы на нужном языке: url_lang('en', 'solutions/') → /en/solutions/
// (на GitHub Pages с префиксом: /tec-inter/en/solutions/)
function url_lang($lang, $path = '')
{
    global $LANGS, $CFG;
    $p = $LANGS[$lang]['prefix'];
    return $CFG['base_path'] . '/' . ($p !== '' ? $p . '/' : '') . ltrim($path, '/');
}
