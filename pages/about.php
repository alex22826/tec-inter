<?php
page_start(array(
    'title' => t('О компании | С 2008 года | TEC International'),
    'description' => t('Системный интегратор полного цикла TEC International работает с 2008 года. Преимущества, сертификаты партнеров и заказчики.'),
    'nav' => 'about',
    'art' => 'servers',
));

$now = date('Y-m');
$TABS = array(
    'about'        => 'О нас',
    'certificates' => 'Сертификаты и благодарности',
    'clients'      => 'Нас выбирают',
);

function clients_grid($items)
{
    $h = '<div class="clients">';
    foreach ($items as $c) {
        $h .= client_tile($c);
    }
    return $h . '</div>';
}

// документы, которые показываем: просроченные скрыты (ТЗ, слайд 12)
$docs = array();
$docTypes = array(); // подгруппы, в которых есть хотя бы один документ
foreach ($DOCS as $doc) {
    $doc['st'] = doc_status($doc['valid'], $now);
    if ($doc['st'] === 'expired') {
        continue;
    }
    $docs[] = $doc;
    $docTypes[$doc['type']] = true;
}
?>
<section class="page-head page-head--tabs">
  <div class="container">
    <?= breadcrumbs(array(t('О компании') => '')) ?>
    <h1><?= e(t('О компании')) ?></h1>
    <div class="tabs" role="tablist"<?= note('Вкладки подразделов: О нас, Сертификаты и благодарности, Нас выбирают') ?>>
      <?php $first = true; foreach ($TABS as $id => $label): ?>
        <a class="tab<?= $first ? ' is-active' : '' ?>" href="#<?= $id ?>" role="tab" data-tab="<?= $id ?>" aria-selected="<?= $first ? 'true' : 'false' ?>"><?= e(t($label)) ?></a>
      <?php $first = false; endforeach; ?>
    </div>
  </div>
</section>

<div class="container tab-panels">
  <section class="tab-panel" data-panel="about" role="tabpanel">
    <div class="about-intro">
      <p class="lead"><?= e(t('TEC International, системный интегратор полного цикла, с 2008 года проектирует, поставляет, внедряет и поддерживает IT-инфраструктуру для госсектора, промышленности и бизнеса по всему Казахстану.')) ?></p>
      <div class="stats stats--3"<?= note('Ключевые цифры компании') ?>>
        <div class="stat"><b>2008</b><span><?= e(t('год основания')) ?></span></div>
        <div class="stat"><b>120+</b><span><?= e(t('проектов')) ?></span></div>
        <div class="stat"><b>30+</b><span><?= e(t('вендоров')) ?></span></div>
      </div>
    </div>

    <div class="art art--shield">
    <h2 class="h3"><?= e(t('Опыт в госзакупках')) ?></h2>
    <div class="stats stats--3"<?= note('Актуальные суммы пришлет TEC') ?>>
      <div class="stat"><b><?= e(t('15,8 млрд ₸')) ?></b><span><?= e(t('поставка товаров')) ?></span></div>
      <div class="stat"><b><?= e(t('3,3 млрд ₸')) ?></b><span><?= e(t('выполнение работ')) ?></span></div>
      <div class="stat"><b><?= e(t('3,6 млрд ₸')) ?></b><span><?= e(t('оказание услуг')) ?></span></div>
    </div>

    <div class="values"<?= note('Миссия, цель и ценности со старого сайта') ?>>
      <div class="value"><h3><?= e(t('Миссия')) ?></h3><p><?= e(t('Помочь заказчикам повысить эффективность и безопасность IT-инфраструктуры, чтобы они могли максимально реализовать потенциал своего бизнеса.')) ?></p></div>
      <div class="value"><h3><?= e(t('Цель')) ?></h3><p><?= e(t('Быть командой профессионалов, которой доверяют самые сложные и инновационные проекты.')) ?></p></div>
      <div class="value"><h3><?= e(t('Ценности')) ?></h3><p><?= e(t('Профессионализм, порядочность, честность, постоянное развитие, лояльность, конфиденциальность.')) ?></p></div>
    </div>

    </div>

    <div class="art art--vpn">
    <h2 class="h3"<?= note('Преимущества нашей компании со страницы «О компании» старого сайта') ?>><?= e(t('Преимущества нашей компании')) ?></h2>
    <div class="advantages">
      <?php foreach ($ADVANTAGES as $adv): ?>
        <div class="advantage"><?= icon('check', 'advantage__ico') ?><p><?= e(t($adv)) ?></p></div>
      <?php endforeach; ?>
    </div>
    </div>

    <div class="box">
      <h2 class="h3"><?= e(t('Нас выбирают')) ?></h2>
      <?= clients_grid(array_slice($CLIENTS, 0, 6)) ?>
    </div>
  </section>

  <section class="tab-panel" data-panel="certificates" role="tabpanel" hidden>
    <div class="chips filter" data-filter="type" role="group" aria-label="<?= e(t('Тип документа')) ?>"<?= note('Подгруппы лицензий и благодарственных писем скрыты, пока в них нет документов, и появятся сами с первым документом') ?>>
      <button class="chip is-active" type="button" data-value="" aria-pressed="true"><?= e(t('Все')) ?></button>
      <?php foreach ($DOC_TYPES as $k => $label): if (empty($docTypes[$k])) continue; ?>
        <button class="chip" type="button" data-value="<?= e($k) ?>" aria-pressed="false"><?= e(t($label)) ?></button>
      <?php endforeach; ?>
    </div>
    <div class="docs" data-filter-list>
      <?php $licenseNoted = false; $vendorSeen = array();
      foreach ($docs as $doc):
          $st = $doc['st'];
          $title = t($doc['title']);
          $noteHtml = '';
          if ($doc['type'] === 'license' && !$licenseNoted) {
              $noteHtml = note('Лицензия с пометкой «дает право» и ссылкой на проверку в реестре');
              $licenseNoted = true;
          }
          // якорь #cert-<вендор>: прямая ссылка на сертификат вендора
          $anchor = '';
          if (!empty($doc['vendor']) && !isset($vendorSeen[$doc['vendor']])) {
              $anchor = ' id="cert-' . e($doc['vendor']) . '"';
              $vendorSeen[$doc['vendor']] = true;
          }
          $file = isset($doc['file']) ? $doc['file'] : '';
          $full = $file ? 'assets/certs/' . (isset($doc['full']) ? $doc['full'] : $file . '.jpg') : '';
          $download = $file ? 'assets/certs/' . (isset($doc['download']) ? $doc['download'] : $file . '.pdf') : '';
      ?>
        <article class="card doc<?= $file ? ' doc--real' : '' ?>" data-type="<?= e($doc['type']) ?>"<?= $anchor ?><?= $noteHtml ?>>
          <?php if ($file): ?>
            <button class="doc__scan" type="button" data-zoom data-full="<?= asset($full) ?>" aria-label="<?= e(t('Увеличить')) ?>: <?= e($title) ?>">
              <img src="<?= asset('assets/certs/' . $file . '-thumb.jpg') ?>" alt="<?= e($title . ', ' . $doc['sub']) ?>" loading="lazy">
            </button>
          <?php else: ?>
            <button class="doc__scan" type="button" data-zoom aria-label="<?= e(t('Увеличить')) ?>: <?= e($title) ?>">
              <span class="doc__paper"><span class="doc__seal"></span><b><?= e($title) ?></b><i></i><i></i><i class="short"></i></span>
            </button>
          <?php endif; ?>
          <h3><?= e($title) ?></h3>
          <p class="doc__sub"><?= e(t($doc['sub'])) ?></p>
          <div class="doc__foot">
            <?php if ($doc['type'] === 'license'): ?>
              <a class="doc__check" href="https://elicense.kz/" target="_blank" rel="noopener"><?= e(t('Проверить на elicense.kz')) ?></a>
            <?php elseif ($st === 'expiring'): ?>
              <span class="doc__valid doc__valid--warn"><?= e(t('истекает')) ?> <?= e(fmt_month($doc['valid'])) ?></span>
            <?php elseif ($st === 'active'): ?>
              <span class="doc__valid"><?= icon('check') ?> <?= e(t('действует до')) ?> <?= e(fmt_month($doc['valid'])) ?></span>
            <?php elseif (!empty($doc['issued'])): ?>
              <span class="doc__valid"><?= icon('check') ?> <?= e(t('письмо от')) ?> <?= e(fmt_date($doc['issued'])) ?></span>
            <?php elseif (!empty($doc['period'])): ?>
              <span class="doc__valid"><?= icon('check') ?> <?= e($doc['period']) ?></span>
            <?php else: ?>
              <span></span>
            <?php endif; ?>
            <?php if ($download): ?>
              <a class="doc__pdf" href="<?= asset($download) ?>" target="_blank" rel="noopener" download><?= e(strtoupper(pathinfo($download, PATHINFO_EXTENSION))) ?> <?= icon('download') ?></a>
            <?php else: ?>
              <button class="doc__pdf" type="button" data-soon="<?= e(t('PDF появится после загрузки сканов через админку')) ?>">PDF <?= icon('download') ?></button>
            <?php endif; ?>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
    <p class="footnote"><?= e(t('* Скан увеличивается по клику. Просроченные документы скрываются автоматически по дате.')) ?></p>
  </section>

  <section class="tab-panel" data-panel="clients" role="tabpanel" hidden>
    <h2 class="h3"><?= e(t('Нас выбирают')) ?></h2>
    <p class="muted"<?= note('Под названием указана отрасль, так видна широта. Логотипы в одном стиле, гос- и квазигос-заказчиков показываем только с согласия') ?>><?= e(t('Заказчики из разных отраслей: от аэронавигации до ритейла.')) ?></p>
    <?= clients_grid($CLIENTS) ?>
  </section>
</div>

<section class="section">
  <div class="container cta-only">
    <button class="btn btn--accent btn--lg" type="button" data-lead><?= e(t('Обсудить проект')) ?></button>
  </div>
</section>
<?php page_end();
