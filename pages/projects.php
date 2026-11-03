<?php
page_start(array(
    'title' => t('Проекты | Что сделали и что получили заказчики | TEC International'),
    'description' => t('Проекты TEC International: серверное и сетевое оборудование, рабочие места, системы безопасности, авиационные специализированные системы.'),
    'nav' => 'projects',
));

// волна под темной шапкой проекта, как на старом сайте: три слоя серого и цвет фона страницы
$wave = '<svg class="proj__wave" viewBox="0 0 1440 120" preserveAspectRatio="none" aria-hidden="true">'
    . '<path d="M0 58C210 18 430 22 690 52S1180 96 1440 34V120H0Z" fill="#4b4b4b"/>'
    . '<path d="M0 84C260 40 560 66 820 60S1250 44 1440 72V120H0Z" fill="#a3a3a3"/>'
    . '<path d="M0 112C210 70 440 72 720 94S1190 104 1440 82V120H0Z" fill="#f6f6f6"/>'
    . '</svg>';
?>
<section class="page-head">
  <div class="container">
    <?= breadcrumbs(array(t('Проекты') => '')) ?>
    <h1><?= e(t('Проекты')) ?></h1>
  </div>
</section>

<?php $gn = 0; foreach ($PROJECT_GROUPS as $g): $gn++; ?>
  <section class="proj-group"<?= $gn === 1 ? note('Проекты, оформление и тексты как на старом сайте, без деления на отрасли') : '' ?>>
    <div class="container">
      <h2 class="h2 proj-group__title"><?= $gn ?>. <?= e(t($g['title'])) ?></h2>
    </div>
    <?php foreach ($g['projects'] as $p): ?>
      <article class="proj" id="<?= e($p['id']) ?>">
        <div class="proj__head">
          <div class="container"><h3><?= e(t($p['title'])) ?></h3></div>
          <?= $wave ?>
        </div>
        <div class="container proj__body">
          <div>
            <h4><?= e(t('Что сделали')) ?></h4>
            <ul class="proj__list">
              <?php foreach ($p['done'] as $it): ?><li><?= icon('edit') ?><?= e(t($it)) ?></li><?php endforeach; ?>
            </ul>
          </div>
          <div>
            <h4><?= e(t('Что получили')) ?></h4>
            <ul class="proj__list proj__list--got">
              <?php foreach ($p['got'] as $it): ?><li><?= icon('check') ?><?= e(t($it)) ?></li><?php endforeach; ?>
            </ul>
          </div>
        </div>
      </article>
    <?php endforeach; ?>
  </section>
<?php endforeach; ?>

<section class="section">
  <div class="container cta-only">
    <button class="btn btn--accent btn--lg" type="button" data-lead><?= e(t('Обсудить проект')) ?></button>
  </div>
</section>
<?php page_end();
