<?php
page_start(array(
    'title' => t('Партнеры и статусы | Cisco, HPE, Fortinet и др. | TEC International'),
    'description' => t('Работаем напрямую с производителями: официальные цены, гарантия и поддержка вендора. Партнеры TEC International по направлениям.'),
    'nav' => 'partners',
));
$groups = array();
foreach ($PARTNERS as $slug => $pt) {
    $groups[$pt['group']][$slug] = $pt;
}
// вендоры, у которых есть сертификат на вкладке «Сертификаты»
$withCert = array();
foreach ($DOCS as $doc) {
    if (!empty($doc['vendor']) && !empty($doc['file'])) {
        $withCert[$doc['vendor']] = true;
    }
}
?>
<section class="page-head">
  <div class="container">
    <?= breadcrumbs(array(t('Партнеры') => '')) ?>
    <h1><?= e(t('Партнеры')) ?></h1>
    <p class="lead"><?= e(t('Работаем напрямую с производителями: официальные цены, гарантия и поддержка вендора.')) ?></p>
  </div>
</section>

<section class="section section--tight">
  <div class="container">
    <?php $g = 0; foreach ($groups as $title => $items): $g++; ?>
      <div class="pgroup"<?= $g === 1 ? note('Группировка по направлениям, под логотипом статус, ссылка на решения вендора') : '' ?>>
        <h2 class="h3"><?= e(t($title)) ?></h2>
        <div class="partners">
          <?php foreach ($items as $slug => $pt): ?>
            <div class="partner" id="<?= e($slug) ?>">
              <div class="partner__logo"><?= e(t($pt['name'])) ?></div>
              <div class="partner__foot">
                <?php if ($pt['status'] && isset($withCert[$slug])): ?>
                  <a class="status status--cert" href="<?= url('about/') ?>#cert-<?= e($slug) ?>" title="<?= e(t('Открыть сертификат')) ?>"><?= icon('check') ?><?= e($pt['status']) ?></a>
                <?php elseif ($pt['status']): ?>
                  <span class="status"><?= e($pt['status']) ?></span>
                <?php else: ?>
                  <span class="status status--todo" title="<?= e(t('Подставить фактический статус из партнерского портала')) ?>"><?= e(t('статус')) ?> ___</span>
                <?php endif; ?>
                <a class="link" href="<?= dir_link($pt['dir']) ?>"><?= e(t('Решения')) ?> →</a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
    <p class="footnote"><?= e(t('* Статусы с галочкой подтверждены сертификатами вендоров, по клику открывается документ. Для остальных партнеров статус подставить из партнерского портала. Только действующие партнерства, сверка списка раз в полгода.')) ?> <?= e(t('Сертификаты партнеров собраны в разделе')) ?> <a href="<?= url('about/') ?>#certificates">«<?= e(t('О компании')) ?> → <?= e(t('Сертификаты')) ?>»</a>.</p>
  </div>
</section>
<?php page_end();
