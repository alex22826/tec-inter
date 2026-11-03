<?php
page_start(array(
    'title' => t('Решения | 10 направлений IT-интеграции | TEC International'),
    'description' => t('Сети, серверы и СХД, ЦОД, информационная безопасность, ПО, виртуализация и бэкап, СКУД и видеонаблюдение, рабочие места, спецсистемы, сервис 24/7.'),
    'nav' => 'solutions',
));
?>
<section class="page-head">
  <div class="container">
    <?= breadcrumbs(array(t('Решения') => '')) ?>
    <h1><?= e(t('Решения')) ?></h1>
    <p class="lead"><?= e(t('10 направлений и один подрядчик. Подбираем, поставляем, внедряем и сопровождаем.')) ?></p>
  </div>
</section>

<section class="section section--tight" id="picker"<?= note('«Подобрать решение»: выбор отрасли подсвечивает подходящие направления') ?>>
  <div class="container">
    <div class="picker">
      <div class="picker__head">
        <h2><?= e(t('Подобрать решение')) ?></h2>
        <p class="muted"><?= e(t('Выберите отрасль, и мы подсветим направления, которые обычно нужны.')) ?></p>
      </div>
      <div class="chips" role="group" aria-label="<?= e(t('Отрасль')) ?>">
        <?php foreach ($INDUSTRIES as $slug => $ind): ?>
          <button class="chip" type="button" data-industry="<?= e($slug) ?>" data-dirs="<?= e(implode(',', $ind['dirs'])) ?>" aria-pressed="false"><?= e(t($ind['title'])) ?></button>
        <?php endforeach; ?>
      </div>
      <div class="picker__result" hidden>
        <p class="picker__text"></p>
        <button class="btn btn--accent btn--sm" type="button" data-lead data-lead-industry=""><?= e(t('Обсудить проект')) ?></button>
        <button class="btn btn--outline btn--sm" type="button" data-picker-reset><?= e(t('Сбросить')) ?></button>
      </div>
    </div>
  </div>
</section>

<section class="section section--tight">
  <div class="container sol__list">
    <?php $n = 0; foreach ($DIRECTIONS as $slug => $d): $n++; ?>
      <article class="sol-item" id="<?= e($slug) ?>" data-dir="<?= e($slug) ?>"<?= $n === 1 ? note('Оформление как на старом сайте: слева описание, справа пункты 01, 02 и производители-партнеры направления') : '' ?>>
        <div class="sol-item__main">
          <h2><?= e(t($d['title'])) ?></h2>
          <?php foreach ((array)$d['text'] as $para): ?><p><?= e(t($para)) ?></p><?php endforeach; ?>
          <?php if ($d['vendors']): ?>
            <div class="sol-item__vendors">
              <span class="muted"><?= e(t('Производители:')) ?></span>
              <?php foreach ($d['vendors'] as $v): ?>
                <a class="vendor" href="<?= url('partners/') ?>#<?= e($v) ?>" title="<?= e(t($PARTNERS[$v]['name'])) ?>"><?= partner_logo($PARTNERS[$v]) ?></a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <div class="sol-item__cta">
            <button class="btn btn--accent" type="button" data-lead data-direction="<?= e($slug) ?>"><?= e(t('Обсудить проект')) ?></button>
            <a class="link" href="<?= url('news/') ?>"><?= e(t('Статьи по теме')) ?> →</a>
          </div>
        </div>
        <ol class="points">
          <?php foreach ($d['items'] as $i => $it): ?>
            <li><span class="points__num"><?= sprintf('%02d', $i + 1) ?></span><b><?= e(t($it)) ?></b></li>
          <?php endforeach; ?>
        </ol>
      </article>
    <?php endforeach; ?>
  </div>
</section>
<?php page_end();
