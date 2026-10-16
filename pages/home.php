<?php
page_start(array(
    'title' => t('TEC International — системный интегратор в Казахстане'),
    'description' => t('Любая IT-задача под ключ: проектирование, поставка оборудования и ПО, интеграция, запуск и поддержка 24/7. Системный интегратор полного цикла с 2008 года.'),
    'nav' => '',
));
?>
<section class="hero"<?= note('Первый экран: заголовок, кнопки «Обсудить проект» и «Подобрать решение»') ?>>
  <div class="container hero__grid">
    <div class="hero__text">
      <p class="eyebrow"><?= e(t('Системный интегратор полного цикла')) ?></p>
      <h1><?= e(t('Любая IT-задача — под ключ. От проекта до поддержки 24/7')) ?></h1>
      <p class="hero__lead"><?= e(t('Проектирование, оборудование, ПО, интеграция, настройка и запуск — для госсектора, промышленности и бизнеса с 2008 года.')) ?></p>
      <div class="hero__btns">
        <button class="btn btn--accent btn--lg" type="button" data-lead><?= e(t('Обсудить проект')) ?></button>
        <a class="btn btn--outline-light btn--lg" href="<?= url('solutions/') ?>#picker"><?= e(t('Подобрать решение')) ?></a>
      </div>
    </div>
    <div class="hero__media">
      <img src="<?= asset('assets/img/hero.svg') ?>" alt="" width="560" height="380">
      <span class="photo-hint"><?= e(t('фото: ЦОД / инженеры на объекте')) ?></span>
    </div>
  </div>
</section>

<section class="section"<?= note('Почему нам доверяют: с 2008 года, 120+ проектов, крупные проекты, эксперты, полный цикл') ?>>
  <div class="container">
    <h2 class="h2"><?= e(t('Почему нам доверяют')) ?></h2>
    <div class="trust">
      <?php foreach ($TRUST as $tr): ?>
        <div class="trust__item"><b><?= e(t($tr[0])) ?></b><span><?= e(t($tr[1])) ?></span></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--tight"<?= note('Направления — 10 плиток, каждая ведёт на своё направление на странице «Решения»') ?>>
  <div class="container">
    <div class="section__head">
      <h2 class="h2"><?= e(t('Направления')) ?></h2>
      <a class="link" href="<?= url('solutions/') ?>"><?= e(t('Все решения')) ?> →</a>
    </div>
    <div class="dirs">
      <?php foreach ($DIRECTIONS as $slug => $d): ?>
        <a class="dir-tile" href="<?= dir_link($slug) ?>">
          <span class="dir-tile__ico"><?= icon($d['icon']) ?></span>
          <b><?= e(t($d['title'])) ?></b>
          <span class="dir-tile__more"><?= e(t('Подробнее')) ?> →</span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--tight"<?= note('Решения для отраслей — 11 отраслей: клиент сразу видит, что задача по адресу') ?>>
  <div class="container">
    <h2 class="h2"><?= e(t('Решения для отраслей')) ?></h2>
    <div class="chips">
      <?php foreach ($INDUSTRIES as $slug => $ind): ?>
        <a class="chip" href="<?= url('solutions/') ?>?industry=<?= e($slug) ?>#picker"><?= e(t($ind['title'])) ?></a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--gray"<?= note('Комплексный подход: проектирование → оборудование → ПО → интеграция → настройка → запуск → поддержка') ?>>
  <div class="container">
    <h2 class="h2"><?= e(t('Комплексный подход')) ?></h2>
    <ol class="steps">
      <?php foreach ($APPROACH as $i => $s): ?>
        <li class="step"><span class="step__num"><?= $i + 1 ?></span><b><?= e(t($s[0])) ?></b><span><?= e(t($s[1])) ?></span></li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>

<section class="section"<?= note('Наши проекты: задача → решение (подборка из раздела «Проекты»)') ?>>
  <div class="container">
    <div class="section__head">
      <h2 class="h2"><?= e(t('Наши проекты')) ?></h2>
      <a class="btn btn--outline btn--sm" href="<?= url('projects/') ?>"><?= e(t('Все проекты')) ?> →</a>
    </div>
    <div class="grid-3">
      <?php foreach (array_slice($PROJECTS, 0, 3) as $p) echo project_card($p); ?>
    </div>
  </div>
</section>

<section class="section section--tight"<?= note('Партнёры — логотипы ведут на страницу партнёров со статусами') ?>>
  <div class="container">
    <div class="section__head">
      <h2 class="h2"><?= e(t('Партнёры')) ?></h2>
      <a class="link" href="<?= url('partners/') ?>"><?= e(t('Все партнёры и статусы')) ?> →</a>
    </div>
    <div class="logos">
      <?php foreach (array_slice($PARTNERS, 0, 12) as $slug => $pt): ?>
        <a class="logo-tile" href="<?= url('partners/') ?>#<?= e($slug) ?>"><?= e(t($pt['name'])) ?></a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--tight"<?= note('Строка «Нас выбирают» — названия заказчиков с отраслью (логотипы гос- и квазигос-клиентов — только с их согласия)') ?>>
  <div class="container">
    <h2 class="h2"><?= e(t('Нас выбирают')) ?></h2>
    <div class="clients clients--row">
      <?php foreach ($CLIENTS as $c): ?>
        <div class="client"><b><?= e(t($c[0])) ?></b><span><?= e(t($c[1])) ?></span></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section" id="lead"<?= note('Форма «Обсудить техническую задачу»') ?>>
  <div class="container">
    <div class="band">
      <div class="band__text">
        <h2><?= e(t('Обсудить техническую задачу')) ?></h2>
        <p><?= e(t('Ответим в течение рабочего дня, проконсультируем и предложим 2–3 варианта решения.')) ?></p>
      </div>
      <?= lead_form('band', 'home') ?>
    </div>
  </div>
</section>
<?php page_end();
