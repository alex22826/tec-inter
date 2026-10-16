<?php
page_start(array(
    'title' => t('Проекты — задача и решение | TEC International'),
    'description' => t('Реализованные проекты TEC International: аэронавигация, энергетика, госсектор, распределённые сети. Задача → решение.'),
    'nav' => 'projects',
));
$used = array();
foreach ($PROJECTS as $p) {
    $used[$p['industry']] = $INDUSTRIES[$p['industry']]['title'];
}
?>
<section class="page-head">
  <div class="container">
    <?= breadcrumbs(array(t('Проекты') => '')) ?>
    <h1><?= e(t('Проекты')) ?></h1>
    <p class="lead"><?= e(t('Задача клиента → наше решение. 120+ проектов с 2008 года — здесь подборка.')) ?></p>
  </div>
</section>

<section class="section section--tight">
  <div class="container">
    <div class="chips filter" data-filter="industry" role="group" aria-label="<?= e(t('Отрасль')) ?>"<?= note('Проекты по отраслям: фильтр по отрасли и ссылка на направление') ?>>
      <button class="chip is-active" type="button" data-value="" aria-pressed="true"><?= e(t('Все отрасли')) ?></button>
      <?php foreach ($used as $slug => $title): ?>
        <button class="chip" type="button" data-value="<?= e($slug) ?>" aria-pressed="false"><?= e(t($title)) ?></button>
      <?php endforeach; ?>
    </div>
    <div class="grid-3" data-filter-list>
      <?php foreach ($PROJECTS as $p) echo project_card($p); ?>
    </div>
    <p class="footnote"><?= e(t('* Подборка — пример для обсуждения: добавить реальные проекты, фото и результат в цифрах; названия заказчиков — только с их согласия.')) ?></p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="band band--compact">
      <div class="band__text">
        <h2><?= e(t('Похожая задача?')) ?></h2>
        <p><?= e(t('Расскажите о проекте — предложим 2–3 варианта решения.')) ?></p>
      </div>
      <button class="btn btn--accent btn--lg" type="button" data-lead><?= e(t('Обсудить проект')) ?></button>
    </div>
  </div>
</section>
<?php page_end();
