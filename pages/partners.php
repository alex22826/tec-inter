<?php
page_start(array(
    'title' => t('Партнеры | Cisco, HPE, Fortinet и др. | TEC International'),
    'description' => t('Работаем напрямую с производителями: официальные цены, гарантия и поддержка вендора. Партнеры TEC International по направлениям.'),
    'nav' => 'partners',
    'art' => 'vpn',
));
$groups = array();
foreach ($PARTNERS as $slug => $pt) {
    $groups[$pt['group']][$slug] = $pt;
}
?>
<section class="page-head">
  <div class="container">
    <?= breadcrumbs(array(t('Партнеры') => '')) ?>
    <h1><?= e(t('Партнеры')) ?></h1>
  </div>
</section>

<section class="section section--tight">
  <div class="container">
    <?php $g = 0; foreach ($groups as $title => $items): $g++; ?>
      <div class="pgroup"<?= $g === 1 ? note('Группировка по направлениям: логотип, под ним название и ссылка на решения вендора') : '' ?>>
        <h2 class="h3"><?= e(t($title)) ?></h2>
        <div class="partners">
          <?php foreach ($items as $slug => $pt): ?>
            <div class="partner" id="<?= e($slug) ?>">
              <div class="partner__logo"><?= partner_logo($pt, false) ?></div>
              <b class="partner__name"><?= e(t($pt['name'])) ?></b>
              <div class="partner__foot">
                <a class="link" href="<?= dir_link($pt['dir']) ?>"><?= e(t('Решения')) ?> →</a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
    <p class="footnote"><?= e(t('Сертификаты партнеров собраны в разделе')) ?> <a href="<?= url('about/') ?>#certificates">«<?= e(t('О компании')) ?> → <?= e(t('Сертификаты')) ?>»</a>.</p>
  </div>
</section>
<?php page_end();
