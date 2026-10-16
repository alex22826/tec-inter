<?php
page_start(array(
    'title' => 'О компании — с 2008 года | TEC International',
    'description' => 'TEC International — системный интегратор полного цикла с 2008 года. История, команда, сертификаты, лицензии и заказчики.',
    'nav' => 'about',
    'path' => '/about/',
));

$now = date('Y-m');
$TABS = array(
    'about'        => 'О нас',
    'history'      => 'История',
    'team'         => 'Команда',
    'certificates' => 'Сертификаты и благодарности',
    'clients'      => 'Нам доверяют',
);

function timeline($items)
{
    $h = '<ol class="timeline">';
    foreach ($items as $it) {
        $h .= '<li><span class="timeline__dot"></span><b>' . e($it[0]) . '</b><span>' . e($it[1]) . '</span></li>';
    }
    return $h . '</ol>';
}

function clients_grid($items)
{
    $h = '<div class="clients">';
    foreach ($items as $c) {
        $h .= '<div class="client"><b>' . e($c[0]) . '</b><span>' . e($c[1]) . '</span></div>';
    }
    return $h . '</div>';
}
?>
<section class="page-head page-head--tabs">
  <div class="container">
    <?= breadcrumbs(array('О компании' => '')) ?>
    <h1>О компании</h1>
    <div class="tabs" role="tablist"<?= note('Вкладки подразделов: О нас, История, Команда, Сертификаты и благодарности, Нам доверяют') ?>>
      <?php $first = true; foreach ($TABS as $id => $label): ?>
        <a class="tab<?= $first ? ' is-active' : '' ?>" href="#<?= $id ?>" role="tab" data-tab="<?= $id ?>" aria-selected="<?= $first ? 'true' : 'false' ?>"><?= e($label) ?></a>
      <?php $first = false; endforeach; ?>
    </div>
  </div>
</section>

<div class="container tab-panels">
  <section class="tab-panel" data-panel="about" role="tabpanel">
    <div class="about-intro">
      <div>
        <p class="lead">TEC International — системный интегратор полного цикла. С 2008 года проектируем, поставляем, внедряем и поддерживаем IT-инфраструктуру для госсектора, промышленности и бизнеса по всему Казахстану.</p>
        <div class="stats"<?= note('Ключевые цифры компании (инженеров — подставить)') ?>>
          <div class="stat"><b>2008</b><span>год основания</span></div>
          <div class="stat"><b>120+</b><span>проектов</span></div>
          <div class="stat"><b>20+</b><span>вендоров</span></div>
          <div class="stat stat--todo"><b>___</b><span>инженеров</span></div>
        </div>
      </div>
      <div class="photo-ph">
        <?= icon('support', 'photo-ph__ico') ?>
        <span>фото команды / офиса</span>
      </div>
    </div>

    <h2 class="h3">Опыт в госзакупках</h2>
    <p class="muted">Подтверждённый объём выполненных контрактов позволяет участвовать в закупках с лимитами:</p>
    <div class="stats stats--3">
      <div class="stat"><b>1,8 млрд ₸</b><span>поставка товаров</span></div>
      <div class="stat"><b>1,3 млрд ₸</b><span>выполнение работ</span></div>
      <div class="stat"><b>1,6 млрд ₸</b><span>оказание услуг</span></div>
    </div>

    <div class="values"<?= note('Миссия и ценности — перенесены с главной') ?>>
      <div class="value"><h3>Миссия</h3><p>Помочь заказчикам повысить эффективность и безопасность IT-инфраструктуры, чтобы они могли максимально реализовать потенциал своего бизнеса.</p></div>
      <div class="value"><h3>Цель</h3><p>Быть командой профессионалов, которой доверяют самые сложные и инновационные проекты.</p></div>
      <div class="value"><h3>Ценности</h3><p>Профессионализм, порядочность, честность, постоянное развитие, лояльность, конфиденциальность.</p></div>
    </div>

    <h2 class="h3">История</h2>
    <?= timeline($HISTORY) ?>

    <div class="box">
      <h2 class="h3">Нам доверяют / Нас выбирают</h2>
      <?= clients_grid(array_slice($CLIENTS, 0, 6)) ?>
    </div>
  </section>

  <section class="tab-panel" data-panel="history" role="tabpanel" hidden>
    <h2 class="h3">История компании</h2>
    <p class="muted"<?= note('История — таймлайн с 2008 года, годы заполнить') ?>>От систем безопасности — к интегратору полного цикла. Годы промежуточных этапов — уточнить.</p>
    <?= timeline($HISTORY) ?>
  </section>

  <section class="tab-panel" data-panel="team" role="tabpanel" hidden>
    <h2 class="h3">Команда и компетенции</h2>
    <p class="muted">Сертифицированные инженеры по каждому направлению. Количество специалистов и фото — подставить.</p>
    <div class="team">
      <?php foreach ($TEAM as $t): ?>
        <div class="team__item">
          <span class="team__ico"><?= icon($t[0]) ?></span>
          <h3><?= e($t[1]) ?></h3>
          <p><?= e($t[2]) ?></p>
          <span class="tag tag--light"><?= e($t[3]) ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="tab-panel" data-panel="certificates" role="tabpanel" hidden>
    <div class="chips filter" data-filter="type" role="group" aria-label="Тип документа"<?= note('Фильтр по типу документа') ?>>
      <button class="chip is-active" type="button" data-value="" aria-pressed="true">Все</button>
      <?php foreach ($DOC_TYPES as $k => $label): ?>
        <button class="chip" type="button" data-value="<?= e($k) ?>" aria-pressed="false"><?= e($label) ?></button>
      <?php endforeach; ?>
    </div>
    <div class="docs" data-filter-list>
      <?php foreach ($DOCS as $i => $doc):
          $st = doc_status($doc['valid'], $now);
          if ($st === 'expired') continue; // просроченные не показываем
      ?>
        <article class="card doc" data-type="<?= e($doc['type']) ?>"<?= $i === 3 ? note('Лицензия с пометкой «даёт право» и ссылкой на проверку в реестре') : '' ?>>
          <button class="doc__scan" type="button" data-zoom aria-label="Увеличить: <?= e($doc['title']) ?>">
            <span class="doc__paper"><span class="doc__seal"></span><b><?= e($doc['title']) ?></b><i></i><i></i><i class="short"></i></span>
          </button>
          <h3><?= e($doc['title']) ?></h3>
          <p class="doc__sub"><?= e($doc['sub']) ?></p>
          <div class="doc__foot">
            <?php if ($doc['type'] === 'license'): ?>
              <a class="doc__check" href="https://elicense.kz/" target="_blank" rel="noopener">Проверить на elicense.kz</a>
            <?php elseif ($st === 'expiring'): ?>
              <span class="doc__valid doc__valid--warn">истекает <?= e(fmt_month($doc['valid'])) ?></span>
            <?php elseif ($st === 'active'): ?>
              <span class="doc__valid"><?= icon('check') ?> действует до <?= e(fmt_month($doc['valid'])) ?></span>
            <?php else: ?>
              <span></span>
            <?php endif; ?>
            <button class="doc__pdf" type="button" data-soon="PDF появится после загрузки сканов через админку">PDF <?= icon('download') ?></button>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
    <p class="footnote">* По клику — увеличение скана. Подписи и сроки — пример, подставить фактические. Загрузка через админку; просроченные документы скрываются автоматически по дате.</p>
  </section>

  <section class="tab-panel" data-panel="clients" role="tabpanel" hidden>
    <h2 class="h3">Нас выбирают</h2>
    <p class="muted"<?= note('Под названием — отрасль: так видна широта. Логотипы в одном стиле, гос- и квазигос — только с согласия') ?>>Заказчики из разных отраслей — от аэронавигации до ритейла.</p>
    <?= clients_grid($CLIENTS) ?>
  </section>
</div>

<section class="section">
  <div class="container">
    <div class="band band--compact">
      <div class="band__text">
        <h2>Обсудим ваш проект?</h2>
        <p>Подготовим 2–3 варианта решения под задачу и бюджет.</p>
      </div>
      <button class="btn btn--accent btn--lg" type="button" data-lead>Обсудить проект</button>
    </div>
  </div>
</section>
<?php page_end();
