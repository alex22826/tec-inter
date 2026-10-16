<?php
page_start(array(
    'title' => 'Партнёры и статусы — Cisco, HPE, Fortinet и др. | TEC International',
    'description' => 'Работаем напрямую с производителями: официальные цены, гарантия и поддержка вендора. Партнёры TEC International по направлениям.',
    'nav' => 'partners',
    'path' => '/partners/',
));
$groups = array();
foreach ($PARTNERS as $slug => $pt) {
    $groups[$pt['group']][$slug] = $pt;
}
?>
<section class="page-head">
  <div class="container">
    <?= breadcrumbs(array('Партнёры' => '')) ?>
    <h1>Партнёры</h1>
    <p class="lead">Работаем напрямую с производителями — официальные цены, гарантия и поддержка вендора.</p>
  </div>
</section>

<section class="section section--tight">
  <div class="container">
    <?php $g = 0; foreach ($groups as $title => $items): $g++; ?>
      <div class="pgroup"<?= $g === 1 ? note('Группировка по направлениям; под логотипом — статус; ссылка на решения вендора') : '' ?>>
        <h2 class="h3"><?= e($title) ?></h2>
        <div class="partners">
          <?php foreach ($items as $slug => $pt): ?>
            <div class="partner" id="<?= e($slug) ?>">
              <div class="partner__logo"><?= e($pt['name']) ?></div>
              <div class="partner__foot">
                <?php if ($pt['status']): ?>
                  <span class="status"><?= e($pt['status']) ?></span>
                <?php else: ?>
                  <span class="status status--todo" title="Подставить фактический статус из партнёрского портала">статус ___</span>
                <?php endif; ?>
                <a class="link" href="<?= dir_link($pt['dir']) ?>">Решения →</a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
    <p class="footnote">* Статусы — подставить фактические (Gold / Silver / Authorized). Только действующие партнёрства, официальные логотипы из партнёрских порталов, сверка списка раз в полгода. Сертификаты партнёров — в разделе <a href="<?= url('about/') ?>#certificates">«О компании → Сертификаты»</a>.</p>
  </div>
</section>
<?php page_end();
