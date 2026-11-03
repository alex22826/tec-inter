<?php
page_start(array(
    'title' => t('TEC International | Системный интегратор в Казахстане'),
    'description' => t('Любая IT-задача под ключ: проектирование, поставка оборудования и ПО, интеграция, запуск и поддержка 24/7. Системный интегратор полного цикла с 2008 года.'),
    'nav' => '',
));

// проекты для блока «Наши проекты»: id из $PROJECTS_HOME, группа нужна для подписи
$homeProjects = array();
foreach ($PROJECT_GROUPS as $g) {
    foreach ($g['projects'] as $p) {
        if (in_array($p['id'], $PROJECTS_HOME, true)) {
            $homeProjects[$p['id']] = array($p, $g['title']);
        }
    }
}
?>
<section class="hero"<?= note('Первый экран: заголовок и одна кнопка «Обсудить проект»') ?>>
  <div class="container hero__grid">
    <div class="hero__text">
      <p class="eyebrow"><?= e(t('Системный интегратор полного цикла')) ?></p>
      <h1><?= e(t('Любая IT-задача под ключ. От проекта до поддержки 24/7')) ?></h1>
      <p class="hero__lead"><?= e(t('Проектирование, оборудование, ПО, интеграция, настройка и запуск для госсектора, промышленности и бизнеса с 2008 года.')) ?></p>
      <div class="hero__btns">
        <button class="btn btn--accent btn--lg" type="button" data-lead><?= e(t('Обсудить проект')) ?></button>
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

<section class="section section--tight"<?= note('Направления: 10 плиток, каждая ведет на свое направление на странице «Решения»') ?>>
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

<section class="section"<?= note('Наши проекты: подборка со страницы «Проекты»') ?>>
  <div class="container">
    <div class="section__head">
      <h2 class="h2"><?= e(t('Наши проекты')) ?></h2>
      <a class="btn btn--outline btn--sm" href="<?= url('projects/') ?>"><?= e(t('Все проекты')) ?> →</a>
    </div>
    <div class="grid-3">
      <?php foreach ($PROJECTS_HOME as $id) echo project_teaser($homeProjects[$id][0], $homeProjects[$id][1]); ?>
    </div>
  </div>
</section>

<section class="section section--tight"<?= note('Партнеры: логотипы ведут на страницу партнеров со статусами') ?>>
  <div class="container">
    <div class="section__head">
      <h2 class="h2"><?= e(t('Партнеры')) ?></h2>
      <a class="link" href="<?= url('partners/') ?>"><?= e(t('Все партнеры и статусы')) ?> →</a>
    </div>
    <div class="logos">
      <?php foreach (array_slice($PARTNERS, 0, 12) as $slug => $pt): ?>
        <a class="logo-tile" href="<?= url('partners/') ?>#<?= e($slug) ?>"><?= partner_logo($pt) ?></a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--tight"<?= note('«Нас выбирают»: логотипы с официальных сайтов заказчиков, где логотипа нет, название') ?>>
  <div class="container">
    <h2 class="h2"><?= e(t('Нас выбирают')) ?></h2>
    <div class="clients clients--row">
      <?php foreach ($CLIENTS as $c) echo client_tile($c, false); ?>
    </div>
  </div>
</section>

<section class="section" id="lead"<?= note('Форма «Обсудить техническую задачу»') ?>>
  <div class="container">
    <div class="band">
      <div class="band__text">
        <h2><?= e(t('Обсудить техническую задачу')) ?></h2>
      </div>
      <?= lead_form('band', 'home') ?>
    </div>
  </div>
</section>
<?php page_end();
