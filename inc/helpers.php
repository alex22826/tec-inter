<?php
// Общие функции: экранирование, ссылки, иконки, даты, шаблон страницы.

function e($s)
{
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

function url($path = '')
{
    return '/' . ltrim($path, '/');
}

// Версия файла в адресе (?v=время изменения) — браузер не держит старый CSS/JS
function asset($path)
{
    $file = dirname(__DIR__) . '/' . ltrim($path, '/');
    $v = is_file($file) ? filemtime($file) : 1;
    return url($path) . '?v=' . $v;
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

function project_card($p)
{
    global $DIRECTIONS, $INDUSTRIES;
    $d = $DIRECTIONS[$p['dir']];
    $h  = '<article class="card project" data-industry="' . e($p['industry']) . '" data-dir="' . e($p['dir']) . '">';
    $h .= cover($d['icon'], 'фото проекта');
    $h .= '<div class="project__body"><span class="tag">' . e($INDUSTRIES[$p['industry']]['title']) . '</span>';
    $h .= '<h3>' . e($p['title']) . '</h3>';
    $h .= '<p><b>Задача:</b> ' . e($p['task']) . '</p>';
    $h .= '<p><b>Решение:</b> ' . e($p['solution']) . '</p>';
    $h .= '<a class="link" href="' . dir_link($p['dir']) . '">' . e($d['title']) . ' →</a></div></article>';
    return $h;
}

function post_card($slug, $p)
{
    global $DIRECTIONS;
    $icon = isset($DIRECTIONS[$p['dir']]) ? $DIRECTIONS[$p['dir']]['icon'] : 'docs';
    $isNews = $p['type'] === 'news';
    $h  = '<article class="card post" data-type="' . e($p['type']) . '" data-rubric="' . e($p['rubric']) . '" data-industry="' . e($p['industry']) . '">';
    $h .= '<a class="post__link" href="' . url('news/' . $slug . '/') . '">';
    $h .= cover($icon, 'обложка', $isNews ? 'cover--news' : '');
    $h .= '<div class="post__body"><div class="post__meta"><span class="tag' . ($isNews ? ' tag--dark' : '') . '">' . ($isNews ? 'Новость' : 'Статья') . '</span> ' . e($p['rubric']) . ' · ' . fmt_date($p['date']) . '</div>';
    $h .= '<h3>' . e($p['title']) . '</h3></div></a></article>';
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
    $h = '<option value="">Направление</option>';
    foreach ($DIRECTIONS as $slug => $d) {
        $h .= '<option value="' . e($slug) . '"' . ($slug === $selected ? ' selected' : '') . '>' . e($d['title']) . '</option>';
    }
    return $h . '<option value="other">Другое / не знаю</option>';
}

// Форма заявки. $variant: 'band' (тёмный блок), 'page' (контакты), 'modal'
function lead_form($variant, $source)
{
    $full = $variant !== 'band';
    $h  = '<form class="lead-form lead-form--' . e($variant) . '" action="' . url('api/lead.php') . '" method="post" novalidate>';
    $h .= '<input type="hidden" name="source" value="' . e($source) . '">';
    $h .= '<input type="hidden" name="ts" value="' . time() . '">';
    // ловушка для ботов: поле скрыто от людей
    $h .= '<label class="hp" aria-hidden="true">Сайт <input type="text" name="website" tabindex="-1" autocomplete="off"></label>';
    $h .= '<div class="form-grid">';
    $h .= '<label class="field"><span>Имя</span><input type="text" name="name" required autocomplete="name" placeholder="Имя"></label>';
    $h .= '<label class="field"><span>Телефон</span><input type="tel" name="phone" required autocomplete="tel" placeholder="+7 (___) ___-__-__"></label>';
    $h .= '<label class="field"><span>Компания</span><input type="text" name="company" autocomplete="organization" placeholder="Компания"></label>';
    $h .= '<label class="field"><span>Направление</span><select name="direction">' . direction_options() . '</select></label>';
    if ($full) {
        $h .= '<label class="field field--wide"><span>Опишите задачу</span><textarea name="message" rows="4" placeholder="Опишите задачу"></textarea></label>';
        $h .= '<label class="field field--wide"><span>Откуда узнали о нас</span><select name="found"><option value="">Откуда узнали о нас</option><option>Поиск Google / Яндекс</option><option>Рекомендация</option><option>Госзакупки</option><option>Выставка, мероприятие</option><option>Другое</option></select></label>';
    }
    $h .= '</div>';
    $h .= '<label class="consent"><input type="checkbox" name="consent" value="1" required> <span>Согласен на обработку персональных данных</span></label>';
    $h .= '<div class="form-foot"><button class="btn btn--accent" type="submit">Отправить заявку</button><p class="form-msg" role="status" aria-live="polite"></p></div>';
    $h .= '</form>';
    return $h;
}

function breadcrumbs($items)
{
    $h = '<nav class="crumbs" aria-label="Хлебные крошки"><a href="' . url() . '">Главная</a>';
    foreach ($items as $label => $href) {
        $h .= ' <span>/</span> ' . ($href ? '<a href="' . e($href) . '">' . e($label) . '</a>' : '<span aria-current="page">' . e($label) . '</span>');
    }
    return $h . '</nav>';
}

function page_start($meta)
{
    global $CFG;
    $nav = isset($meta['nav']) ? $meta['nav'] : '';
    $canonical = $CFG['site_url'] . (isset($meta['path']) ? $meta['path'] : '/');
    $menu = array(
        ''          => 'Главная',
        'solutions' => 'Решения',
        'projects'  => 'Проекты',
        'partners'  => 'Партнёры',
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
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($meta['title']) ?></title>
<meta name="description" content="<?= e($meta['description']) ?>">
<?php if ($CFG['demo']): ?><meta name="robots" content="noindex, nofollow">
<?php endif; ?>
<link rel="canonical" href="<?= e($canonical) ?>">
<meta property="og:type" content="website">
<meta property="og:title" content="<?= e($meta['title']) ?>">
<meta property="og:description" content="<?= e($meta['description']) ?>">
<meta property="og:url" content="<?= e($canonical) ?>">
<meta name="theme-color" content="#14213d">
<link rel="icon" href="<?= asset('assets/img/favicon.svg') ?>" type="image/svg+xml">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
<script type="application/ld+json"><?= json_encode($org, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
<?php if (!empty($meta['jsonld'])): ?><script type="application/ld+json"><?= json_encode($meta['jsonld'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
<?php endif; ?>
</head>
<body>
<?php if ($CFG['demo']): ?>
<div class="demo-bar">
  <div class="container demo-bar__in">
    <span><b>Демо по презентации.</b> Тексты, цифры, статусы и фото — примеры для обсуждения.</span>
    <button class="demo-bar__btn" type="button" data-notes-toggle aria-pressed="false">Показать пометки ТЗ</button>
  </div>
</div>
<?php endif; ?>
<header class="header">
  <div class="container header__in">
    <a class="logo" href="<?= url() ?>" aria-label="TEC International — на главную">TEC<i>.</i> <span>International</span></a>
    <nav class="nav" id="nav" aria-label="Главное меню">
      <?php foreach ($menu as $slug => $label): ?>
        <a href="<?= url($slug ? $slug . '/' : '') ?>"<?= $slug === $nav ? ' class="is-active" aria-current="page"' : '' ?>><?= e($label) ?></a>
      <?php endforeach; ?>
      <div class="nav__mobile-extra">
        <a class="btn btn--ghost" href="<?= e($CFG['servicedesk']) ?>" target="_blank" rel="noopener">Service Desk</a>
        <a class="nav__phone" href="tel:<?= e($CFG['phone_href']) ?>"><?= e($CFG['phone']) ?></a>
      </div>
    </nav>
    <div class="header__side">
      <div class="lang"<?= note('Языки RU / KZ / EN — в шапке. KZ и EN — ручной перевод ключевых страниц') ?>>
        <a class="is-active" href="<?= url() ?>">RU</a><span>·</span><button type="button" data-soon="Казахская версия — после утверждения русской">KZ</button><span>·</span><button type="button" data-soon="Английская версия — после утверждения русской">EN</button>
      </div>
      <a class="btn btn--ghost btn--sm header__sd" href="<?= e($CFG['servicedesk']) ?>" target="_blank" rel="noopener"<?= note('Service Desk остаётся отдельной кнопкой в шапке') ?>>Service Desk</a>
      <button class="btn btn--accent btn--sm" type="button" data-lead<?= note('Кнопка открывает форму заявки (сейчас на сайте ведёт на «О компании»)') ?>>Обсудить проект</button>
      <button class="burger" type="button" aria-label="Меню" aria-controls="nav" aria-expanded="false"><?= icon('menu') ?></button>
    </div>
  </div>
</header>
<main id="main">
<?php
}

function page_end()
{
    global $CFG, $DIRECTIONS;
    ?>
</main>
<footer class="footer">
  <div class="container footer__grid">
    <div>
      <a class="logo logo--light" href="<?= url() ?>">TEC<i>.</i> <span>International</span></a>
      <p class="footer__tag">Системный интегратор полного цикла с <?= (int)$CFG['founded'] ?> года: от проекта и поставки до поддержки 24/7.</p>
    </div>
    <div>
      <h3>Компания</h3>
      <a href="<?= url('about/') ?>">О компании</a>
      <a href="<?= url('projects/') ?>">Проекты</a>
      <a href="<?= url('partners/') ?>">Партнёры</a>
      <a href="<?= url('news/') ?>">Новости и статьи</a>
      <a href="<?= url('contacts/') ?>">Контакты</a>
    </div>
    <div>
      <h3>Решения</h3>
      <?php $i = 0; foreach ($DIRECTIONS as $slug => $d): if ($i++ >= 5) break; ?>
        <a href="<?= dir_link($slug) ?>"><?= e($d['title']) ?></a>
      <?php endforeach; ?>
      <a href="<?= url('solutions/') ?>">Все направления →</a>
    </div>
    <div>
      <h3>Связаться</h3>
      <a class="footer__strong" href="tel:<?= e($CFG['phone_href']) ?>"><?= e($CFG['phone']) ?></a>
      <a href="mailto:<?= e($CFG['email']) ?>"><?= e($CFG['email']) ?></a>
      <a href="<?= e($CFG['servicedesk']) ?>" target="_blank" rel="noopener">Service Desk 24/7</a>
    </div>
  </div>
  <div class="container footer__bottom">
    <span>© <?= date('Y') ?> TEC International</span>
    <span>Адрес — на странице <a href="<?= url('contacts/') ?>">«Контакты»</a></span>
  </div>
</footer>

<div class="modal" id="lead-modal" hidden>
  <div class="modal__backdrop" data-close></div>
  <div class="modal__box" role="dialog" aria-modal="true" aria-labelledby="lead-modal-title">
    <button class="modal__close" type="button" data-close aria-label="Закрыть"><?= icon('close') ?></button>
    <h2 id="lead-modal-title">Обсудить проект</h2>
    <p class="muted">Ответим в течение рабочего дня и предложим 2–3 варианта решения.</p>
    <?= lead_form('modal', 'modal') ?>
  </div>
</div>
<div class="lightbox" id="lightbox" hidden>
  <div class="modal__backdrop" data-close></div>
  <figure class="lightbox__box">
    <button class="modal__close" type="button" data-close aria-label="Закрыть"><?= icon('close') ?></button>
    <div class="lightbox__img"></div>
    <figcaption></figcaption>
  </figure>
</div>
<div class="toast" role="status" aria-live="polite"></div>
<script src="<?= asset('assets/js/main.js') ?>" defer></script>
</body>
</html>
<?php
}
