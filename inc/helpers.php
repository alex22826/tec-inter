<?php
// Общие функции: экранирование, ссылки, иконки, даты, шаблон страницы.

function e($s)
{
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

// Ссылка на страницу сайта на текущем языке
function url($path = '')
{
    global $LANG;
    return url_lang($LANG, $path);
}

// Версия файла в адресе (?v=время изменения) — браузер не держит старый CSS/JS.
// Файлы общие для всех языков, поэтому без языкового префикса.
function asset($path)
{
    global $CFG;
    $path = ltrim($path, '/');
    $file = dirname(__DIR__) . '/' . $path;
    $v = is_file($file) ? filemtime($file) : 1;
    return $CFG['base_path'] . '/' . $path . '?v=' . $v;
}

function fmt_date($ymd)
{
    $t = strtotime($ymd);
    return $t ? date('d.m.Y', $t) : $ymd;
}

// 'ГГГГ-ММ' → 'ММ.ГГГГ'
function fmt_month($ym)
{
    $p = explode('-', $ym);
    return count($p) === 2 ? $p[1] . '.' . $p[0] : $ym;
}

/**
 * Статус документа на вкладке «Сертификаты» по сроку действия.
 *
 * $valid — срок действия 'ГГГГ-ММ' или '' (бессрочный: лицензия, письмо);
 * $now   — текущий месяц 'ГГГГ-ММ'.
 * Возвращает: 'none' (без срока) | 'active' | 'expiring' (скоро истекает) | 'expired'.
 *
 * Карточки 'expired' на сайте скрываются (ТЗ, слайд 12), у 'expiring' — пометка.
 */
function doc_status($valid, $now)
{
    if ($valid === '') {
        return 'none';
    }
    // TODO: правило сроков — см. пояснение в чате.
    return 'active';
}

function icon($name, $cls = '')
{
    static $P = array(
        'network'    => '<circle cx="12" cy="5" r="2.5"/><circle cx="5" cy="19" r="2.5"/><circle cx="19" cy="19" r="2.5"/><path d="M12 7.5v4M12 11.5l-5.2 5.4M12 11.5l5.2 5.4"/>',
        'server'     => '<rect x="3" y="4" width="18" height="7" rx="1.5"/><rect x="3" y="13" width="18" height="7" rx="1.5"/><path d="M7 7.5h.01M7 16.5h.01M11 7.5h6M11 16.5h6"/>',
        'datacenter' => '<rect x="4" y="3" width="7" height="18" rx="1"/><rect x="13" y="3" width="7" height="18" rx="1"/><path d="M6.5 7h2M6.5 10h2M15.5 7h2M15.5 10h2M7.5 17h.01M16.5 17h.01"/>',
        'shield'     => '<path d="M12 3l7 3v5c0 4.5-3 8.3-7 10-4-1.7-7-5.5-7-10V6l7-3z"/><path d="M9 12l2 2 4-4"/>',
        'code'       => '<path d="M8 8l-4 4 4 4M16 8l4 4-4 4M13.5 5l-3 14"/>',
        'backup'     => '<ellipse cx="12" cy="5.5" rx="7" ry="2.5"/><path d="M5 5.5v13c0 1.4 3.1 2.5 7 2.5s7-1.1 7-2.5v-13"/><path d="M5 12c0 1.4 3.1 2.5 7 2.5s7-1.1 7-2.5"/>',
        'cloud'      => '<path d="M7 19h10.5a4.5 4.5 0 0 0 .6-8.96A6.5 6.5 0 0 0 5.6 11.1 4 4 0 0 0 7 19z"/>',
        'camera'     => '<rect x="3" y="7" width="13" height="10" rx="2"/><path d="M16 11l5-3v8l-5-3"/>',
        'laptop'     => '<rect x="4" y="5" width="16" height="11" rx="1.5"/><path d="M2 19h20"/>',
        'radar'      => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><path d="M12 12l6-6"/><circle cx="12" cy="12" r="1"/>',
        'support'    => '<path d="M4 14v-2a8 8 0 0 1 16 0v2"/><rect x="3" y="14" width="4" height="6" rx="1.5"/><rect x="17" y="14" width="4" height="6" rx="1.5"/><path d="M19 20c0 1-1.5 2-4 2h-2"/>',
        'docs'       => '<path d="M7 3h7l5 5v13H7z"/><path d="M14 3v5h5M10 13h6M10 17h6"/>',
        'arrow'      => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'phone'      => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2"/>',
        'mail'       => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>',
        'clock'      => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'pin'        => '<path d="M12 21s-7-6.2-7-11a7 7 0 0 1 14 0c0 4.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/>',
        'menu'       => '<path d="M4 7h16M4 12h16M4 17h16"/>',
        'close'      => '<path d="M6 6l12 12M18 6L6 18"/>',
        'check'      => '<path d="M5 12l5 5L20 7"/>',
        'edit'       => '<path d="M11 4H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-6"/><path d="M17.5 3.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4z"/>',
        'download'   => '<path d="M12 4v11M7 10l5 5 5-5M5 20h14"/>',
        'external'   => '<path d="M14 4h6v6M20 4l-9 9M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5"/>',
    );
    $d = isset($P[$name]) ? $P[$name] : '';
    return '<svg class="ico ' . e($cls) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $d . '</svg>';
}

// Пометка из презентации: в режиме «Пометки ТЗ» показывается номер с пояснением
function note($text)
{
    return ' data-note="' . e($text) . '"';
}

// Обложка-заглушка с иконкой направления (вместо реального фото)
function cover($icon, $caption = '', $cls = '')
{
    return '<div class="cover ' . e($cls) . '">' . icon($icon, 'cover__ico') . ($caption ? '<span class="cover__cap">' . e($caption) . '</span>' : '') . '</div>';
}

// Карточка проекта для главной: группа, название и первые пункты «Что получили»
function project_teaser($p, $group)
{
    $h  = '<article class="card project"><div class="project__body">';
    $h .= '<span class="tag">' . e(t($group)) . '</span>';
    $h .= '<h3>' . e(t($p['title'])) . '</h3><ul class="project__got">';
    foreach (array_slice($p['got'], 0, 4) as $g) {
        $h .= '<li>' . icon('check') . e(t($g)) . '</li>';
    }
    $h .= '</ul><a class="link" href="' . url('projects/') . '#' . e($p['id']) . '">' . e(t('Подробнее')) . ' →</a></div></article>';
    return $h;
}

// Логотип партнера. $alt = false, когда название и так написано рядом
function partner_logo($pt, $alt = true)
{
    // логотип еще не добавлен: показываем название тем же блоком, что и картинку
    if ($pt['logo'] === '') {
        return '<span class="logo-text">' . e(t($pt['name'])) . '</span>';
    }
    return '<img src="' . asset('assets/img/partners/' . $pt['logo']) . '" alt="' . ($alt ? e(t($pt['name'])) : '') . '" loading="lazy">';
}

// Заказчик: логотип, если он есть, и название. $industry — подпись с отраслью
function client_tile($c, $industry = true)
{
    $h = '<div class="client' . ($c[2] ? ' client--logo' : '') . '">';
    if ($c[2]) {
        // название написано рядом, поэтому alt пустой
        $h .= '<img src="' . asset('assets/img/clients/' . $c[2]) . '" alt="" loading="lazy">';
    }
    $h .= '<b>' . e(t($c[0])) . '</b>';
    if ($industry) {
        $h .= '<span>' . e(t($c[1])) . '</span>';
    }
    return $h . '</div>';
}

function post_card($slug, $p)
{
    global $DIRECTIONS;
    $icon = isset($DIRECTIONS[$p['dir']]) ? $DIRECTIONS[$p['dir']]['icon'] : 'docs';
    $isNews = $p['type'] === 'news';
    // data-rubric — русское значение (по нему фильтруем), подпись — перевод
    $h  = '<article class="card post" data-type="' . e($p['type']) . '" data-rubric="' . e($p['rubric']) . '" data-industry="' . e($p['industry']) . '">';
    $h .= '<a class="post__link" href="' . url('news/' . $slug . '/') . '">';
    $h .= cover($icon, '', $isNews ? 'cover--news' : '');
    $h .= '<div class="post__body"><div class="post__meta"><span class="tag' . ($isNews ? ' tag--dark' : '') . '">' . e($isNews ? t('Новость') : t('Статья')) . '</span> ' . e(t($p['rubric'])) . ' · ' . fmt_date($p['date']) . '</div>';
    $h .= '<h3>' . e(t_post($slug, 'title', $p['title'])) . '</h3></div></a></article>';
    return $h;
}

function dir_link($slug)
{
    return url('solutions/') . '#' . $slug;
}

// Выпадающий список направлений для форм заявки
function direction_options($selected = '')
{
    global $DIRECTIONS;
    $h = '<option value="">' . e(t('Направление')) . '</option>';
    foreach ($DIRECTIONS as $slug => $d) {
        $h .= '<option value="' . e($slug) . '"' . ($slug === $selected ? ' selected' : '') . '>' . e(t($d['title'])) . '</option>';
    }
    return $h . '<option value="other">' . e(t('Другое / не знаю')) . '</option>';
}

// Форма заявки. $variant: 'band' (тёмный блок), 'page' (контакты), 'modal'
function lead_form($variant, $source)
{
    global $LANG, $CFG;
    $full = $variant !== 'band';
    $found = array('Поиск Google / Яндекс', 'Рекомендация', 'Госзакупки', 'Выставка, мероприятие', 'Другое');
    // на GitHub Pages нет PHP: форма не отправляется, main.js показывает пометку о демо
    $h  = '<form class="lead-form lead-form--' . e($variant) . '" action="' . e($CFG['base_path']) . '/api/lead.php" method="post" novalidate'
        . ($CFG['static'] ? ' data-static' : '') . '>';
    $h .= '<input type="hidden" name="source" value="' . e($source) . '">';
    $h .= '<input type="hidden" name="lang" value="' . e($LANG) . '">';
    $h .= '<input type="hidden" name="ts" value="' . time() . '">';
    // ловушка для ботов: поле скрыто от людей
    $h .= '<label class="hp" aria-hidden="true">Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label>';
    $h .= '<div class="form-grid">';
    $h .= '<label class="field"><span>' . e(t('Имя')) . '</span><input type="text" name="name" required autocomplete="name" placeholder="' . e(t('Имя')) . '"></label>';
    $h .= '<label class="field"><span>' . e(t('Телефон')) . '</span><input type="tel" name="phone" required autocomplete="tel" placeholder="+7 (___) ___-__-__"></label>';
    $h .= '<label class="field"><span>' . e(t('Компания')) . '</span><input type="text" name="company" autocomplete="organization" placeholder="' . e(t('Компания')) . '"></label>';
    $h .= '<label class="field"><span>' . e(t('Направление')) . '</span><select name="direction">' . direction_options() . '</select></label>';
    if ($full) {
        $h .= '<label class="field field--wide"><span>' . e(t('Опишите задачу')) . '</span><textarea name="message" rows="4" placeholder="' . e(t('Опишите задачу')) . '"></textarea></label>';
        $h .= '<label class="field field--wide"><span>' . e(t('Откуда узнали о нас')) . '</span><select name="found"><option value="">' . e(t('Откуда узнали о нас')) . '</option>';
        foreach ($found as $f) {
            // в заявку уходит русское значение — так проще считать источники
            $h .= '<option value="' . e($f) . '">' . e(t($f)) . '</option>';
        }
        $h .= '</select></label>';
    }
    $h .= '</div>';
    $h .= '<label class="consent"><input type="checkbox" name="consent" value="1" required> <span>' . e(t('Согласен на обработку персональных данных')) . '</span></label>';
    $h .= '<div class="form-foot"><button class="btn btn--accent" type="submit">' . e(t('Отправить')) . '</button><p class="form-msg" role="status" aria-live="polite"></p></div>';
    $h .= '</form>';
    return $h;
}

function breadcrumbs($items)
{
    $h = '<nav class="crumbs" aria-label="' . e(t('Хлебные крошки')) . '"><a href="' . url() . '">' . e(t('Главная')) . '</a>';
    foreach ($items as $label => $href) {
        $h .= ' <span>/</span> ' . ($href ? '<a href="' . e($href) . '">' . e($label) . '</a>' : '<span aria-current="page">' . e($label) . '</span>');
    }
    return $h . '</nav>';
}

// Переключатель языков: ведёт на ту же страницу на другом языке
function lang_switch($cls = '')
{
    global $LANGS, $LANG, $PAGE_PATH;
    $h = '<div class="lang ' . e($cls) . '" role="group" aria-label="' . e(t('Язык сайта')) . '"' . ($cls ? '' : note('Переключатель языков RU / KZ / EN в шапке. Ключевые страницы на KZ и EN переведены вручную')) . '>';
    $i = 0;
    foreach ($LANGS as $code => $L) {
        $h .= ($i++ ? '<span>·</span>' : '') . '<a href="' . e(url_lang($code, $PAGE_PATH)) . '" hreflang="' . e($L['html']) . '" lang="' . e($L['html']) . '" data-lang-link'
            . ($code === $LANG ? ' class="is-active" aria-current="true"' : '') . '>' . e($L['label']) . '</a>';
    }
    return $h . '</div>';
}

function page_start($meta)
{
    global $CFG, $LANG, $LANGS, $PAGE_PATH;
    $nav = isset($meta['nav']) ? $meta['nav'] : '';
    $canonical = $CFG['site_url'] . url($PAGE_PATH);
    $menu = array(
        ''          => 'Главная',
        'solutions' => 'Решения',
        'projects'  => 'Проекты',
        'partners'  => 'Партнеры',
        'about'     => 'О компании',
        'news'      => 'Новости и статьи',
        'contacts'  => 'Контакты',
    );
    $org = array(
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'TEC International',
        'url' => $CFG['site_url'],
        'foundingDate' => (string)$CFG['founded'],
        'email' => $CFG['email'],
        'telephone' => $CFG['phone'],
        'address' => array('@type' => 'PostalAddress', 'addressLocality' => 'Астана', 'addressCountry' => 'KZ', 'streetAddress' => 'район Сарыарка, микрорайон Самал, здание 12'),
    );
    ?><!doctype html>
<html lang="<?= e($LANGS[$LANG]['html']) ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($meta['title']) ?></title>
<meta name="description" content="<?= e($meta['description']) ?>">
<?php if ($CFG['demo']): ?><meta name="robots" content="noindex, nofollow">
<?php endif; ?>
<link rel="canonical" href="<?= e($canonical) ?>">
<?php foreach ($LANGS as $code => $L): ?><link rel="alternate" hreflang="<?= e($L['html']) ?>" href="<?= e($CFG['site_url'] . url_lang($code, $PAGE_PATH)) ?>">
<?php endforeach; ?><link rel="alternate" hreflang="x-default" href="<?= e($CFG['site_url'] . url_lang('ru', $PAGE_PATH)) ?>">
<meta property="og:type" content="website">
<meta property="og:locale" content="<?= e($LANGS[$LANG]['locale']) ?>">
<meta property="og:title" content="<?= e($meta['title']) ?>">
<meta property="og:description" content="<?= e($meta['description']) ?>">
<meta property="og:url" content="<?= e($canonical) ?>">
<meta name="theme-color" content="#ffffff">
<link rel="icon" href="<?= asset('assets/img/favicon.svg') ?>" type="image/svg+xml">
<link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
<script>
// Где нет View Transitions между страницами (Firefox), страница проявляется своей анимацией.
// Класс ставим до первой отрисовки, иначе контент мигнет.
if (!window.CSSViewTransitionRule) document.documentElement.className += ' no-vt';
</script>
<script type="application/ld+json"><?= json_encode($org, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
<?php if (!empty($meta['jsonld'])): ?><script type="application/ld+json"><?= json_encode($meta['jsonld'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
<?php endif; ?>
</head>
<body>
<div class="bg-scene" aria-hidden="true"><i></i><i></i><i></i></div>
<?php // полоса «Демо по презентации» с кнопкой «Показать пометки ТЗ» убрана по просьбе TEC (2026-11-10) ?>
<header class="header">
  <div class="container header__in">
    <a class="logo" href="<?= url() ?>" aria-label="<?= e(t('TEC International: на главную')) ?>"><img src="<?= asset('assets/img/logo-dark.png') ?>" width="109" height="46" alt="TEC International"></a>
    <nav class="nav" id="nav" aria-label="<?= e(t('Главное меню')) ?>">
      <?php foreach ($menu as $slug => $label): ?>
        <a href="<?= url($slug ? $slug . '/' : '') ?>"<?= $slug === $nav ? ' class="is-active" aria-current="page"' : '' ?>><?= e(t_menu($label)) ?></a>
      <?php endforeach; ?>
      <div class="nav__mobile-extra">
        <?= lang_switch('lang--mobile') ?>
        <a class="btn btn--ghost" href="<?= e($CFG['servicedesk']) ?>" target="_blank" rel="noopener">Service Desk</a>
        <a class="nav__phone" href="tel:<?= e($CFG['phone_href']) ?>"><?= e($CFG['phone']) ?></a>
      </div>
    </nav>
    <div class="header__side">
      <?= lang_switch() ?>
      <a class="btn btn--ghost btn--sm header__sd" href="<?= e($CFG['servicedesk']) ?>" target="_blank" rel="noopener"<?= note('Service Desk остается отдельной кнопкой в шапке') ?>>Service Desk</a>
      <button class="btn btn--accent btn--sm" type="button" data-lead<?= note('Кнопка открывает форму заявки. На текущем сайте она ведет на «О компании»') ?>><?= e(t('Обсудить проект')) ?></button>
      <button class="burger" type="button" aria-label="<?= e(t('Меню')) ?>" aria-controls="nav" aria-expanded="false"><?= icon('menu', 'burger__open') ?><?= icon('close', 'burger__close') ?></button>
    </div>
  </div>
</header>
<script>
// Если меню не влезает в шапку (длинные подписи на казахском) — «бургер» и на широком экране.
// Встроено сразу после шапки, чтобы сработать до первой отрисовки.
(function () {
  var h = document.querySelector('.header'), row = h.querySelector('.header__in');
  function fit() {
    h.classList.remove('is-compact');
    if (row.scrollWidth > row.clientWidth + 1) h.classList.add('is-compact');
  }
  fit();
  window.addEventListener('resize', fit);
  if (document.fonts && document.fonts.ready) document.fonts.ready.then(fit);
})();
</script>
<main id="main"<?= empty($meta['art']) ? '' : ' class="art art--' . e($meta['art']) . '"' ?>>
<?php
}

function page_end()
{
    global $CFG, $DIRECTIONS, $LANG, $I18N_MISSING;
    // строки для main.js на текущем языке
    $js = array(
        'fill'      => t('Заполните имя, телефон и отметьте согласие'),
        'sending'   => t('Отправляем заявку'),
        'sent'      => t('Заявка отправлена'),
        'sentText'  => t('Свяжемся с вами в течение рабочего дня.'),
        'fail'      => t('Не удалось отправить. Позвоните нам.'),
        'staticTitle' => t('Это демо-копия сайта'),
        'staticText'  => t('Здесь заявки не отправляются. Рабочая форма есть на сайте компании.'),
        'industry'  => t('Отрасль:'),
        'picker'    => t('Для отрасли «%1» обычно нужны: %2.'),
        'notesShow' => t('Показать пометки ТЗ'),
        'notesHide' => t('Скрыть пометки ТЗ'),
        'note'      => t('Пометка'),
    );
    ?>
</main>
<footer class="footer">
  <div class="container footer__grid">
    <div>
      <a class="logo" href="<?= url() ?>"><img src="<?= asset('assets/img/logo-light.png') ?>" width="123" height="52" alt="TEC International" loading="lazy"></a>
      <p class="footer__tag"><?= e(sprintf(t('Системный интегратор полного цикла с %d года: от проекта и поставки до поддержки 24/7.'), $CFG['founded'])) ?></p>
    </div>
    <div>
      <h3><?= e(t('Компания')) ?></h3>
      <a href="<?= url('about/') ?>"><?= e(t('О компании')) ?></a>
      <a href="<?= url('projects/') ?>"><?= e(t('Проекты')) ?></a>
      <a href="<?= url('partners/') ?>"><?= e(t('Партнеры')) ?></a>
      <a href="<?= url('news/') ?>"><?= e(t('Новости и статьи')) ?></a>
      <a href="<?= url('contacts/') ?>"><?= e(t('Контакты')) ?></a>
    </div>
    <div>
      <h3><?= e(t('Решения')) ?></h3>
      <?php $i = 0; foreach ($DIRECTIONS as $slug => $d): if ($i++ >= 5) break; ?>
        <a href="<?= dir_link($slug) ?>"><?= e(t($d['title'])) ?></a>
      <?php endforeach; ?>
      <a href="<?= url('solutions/') ?>"><?= e(t('Все направления')) ?> →</a>
    </div>
    <div>
      <h3><?= e(t('Связаться')) ?></h3>
      <a class="footer__strong" href="tel:<?= e($CFG['phone_href']) ?>"><?= e($CFG['phone']) ?></a>
      <a href="mailto:<?= e($CFG['email']) ?>"><?= e($CFG['email']) ?></a>
      <a href="<?= e($CFG['servicedesk']) ?>" target="_blank" rel="noopener">Service Desk 24/7</a>
    </div>
  </div>
  <div class="container footer__bottom">
    <span>© <?= date('Y') ?> TEC International</span>
    <span><?= e(t('Адрес указан на странице')) ?> <a href="<?= url('contacts/') ?>">«<?= e(t('Контакты')) ?>»</a></span>
  </div>
</footer>

<div class="modal" id="lead-modal" hidden>
  <div class="modal__backdrop" data-close></div>
  <div class="modal__box" role="dialog" aria-modal="true" aria-labelledby="lead-modal-title">
    <button class="modal__close" type="button" data-close aria-label="<?= e(t('Закрыть')) ?>"><?= icon('close') ?></button>
    <h2 id="lead-modal-title"><?= e(t('Обсудить проект')) ?></h2>
    <p class="muted"><?= e(t('Ответим в течение рабочего дня и предложим 2-3 варианта решения.')) ?></p>
    <?= lead_form('modal', 'modal') ?>
  </div>
</div>
<div class="lightbox" id="lightbox" hidden>
  <div class="modal__backdrop" data-close></div>
  <figure class="lightbox__box">
    <button class="modal__close" type="button" data-close aria-label="<?= e(t('Закрыть')) ?>"><?= icon('close') ?></button>
    <div class="lightbox__img"></div>
    <figcaption></figcaption>
  </figure>
</div>
<button class="to-top" type="button" aria-label="<?= e(t('Наверх')) ?>" hidden><?= icon('arrow') ?></button>
<div class="toast" role="status" aria-live="polite"></div>
<script>window.TEC_I18N = <?= json_encode($js, JSON_UNESCAPED_UNICODE) ?>;</script>
<script src="<?= asset('assets/js/main.js') ?>" defer></script>
</body>
</html>
<?php
    // Проверка переводов: /en/...?i18n_missing=1 — список строк без перевода в комментарии
    if ($CFG['demo'] && $LANG !== 'ru' && isset($_GET['i18n_missing'])) {
        echo "<!--I18N_MISSING\n" . json_encode(array_keys($I18N_MISSING), JSON_UNESCAPED_UNICODE) . "\n-->";
    }
}
