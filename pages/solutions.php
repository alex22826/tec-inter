<?php
page_start(array(
    'title' => t('Решения — 10 направлений IT-интеграции | TEC International'),
    'description' => t('Сети, серверы и СХД, ЦОД, информационная безопасность, ПО, виртуализация и бэкап, СКУД и видеонаблюдение, рабочие места, спецсистемы, сервис 24/7.'),
    'nav' => 'solutions',
));

// проекты по направлениям — для блока «Проекты по направлению»
$byDir = array();
foreach ($PROJECTS as $p) {
    $byDir[$p['dir']][] = $p;
}
?>
<section class="page-head">
  <div class="container">
    <?= breadcrumbs(array(t('Решения') => '')) ?>
    <h1><?= e(t('Решения')) ?></h1>
    <p class="lead"><?= e(t('10 направлений — один подрядчик. Подбираем, поставляем, внедряем и сопровождаем.')) ?></p>
  </div>
</section>

<section class="section section--tight" id="picker"<?= note('«Подобрать решение»: выбор отрасли подсвечивает подходящие направления') ?>>
  <div class="container">
    <div class="picker">
      <div class="picker__head">
        <h2><?= e(t('Подобрать решение')) ?></h2>
        <p class="muted"><?= e(t('Выберите отрасль — подсветим направления, которые обычно нужны.')) ?></p>
      </div>
      <div class="chips" role="group" aria-label="<?= e(t('Отрасль')) ?>">
        <?php foreach ($INDUSTRIES as $slug => $ind): ?>
          <button class="chip" type="button" data-industry="<?= e($slug) ?>" data-dirs="<?= e(implode(',', $ind['dirs'])) ?>" aria-pressed="false"><?= e(t($ind['title'])) ?></button>
        <?php endforeach; ?>
      </div>
      <div class="picker__result" hidden>
        <p class="picker__text"></p>
        <button class="btn btn--accent btn--sm" type="button" data-lead data-lead-industry=""><?= e(t('Обсудить задачу')) ?></button>
        <button class="btn btn--outline btn--sm" type="button" data-picker-reset><?= e(t('Сбросить')) ?></button>
      </div>
    </div>
  </div>
</section>

<section class="section section--tight">
  <div class="container sol">
    <aside class="sol__nav" aria-label="<?= e(t('Направления')) ?>">
      <?php $n = 0; foreach ($DIRECTIONS as $slug => $d): $n++; ?>
        <a href="#<?= e($slug) ?>" data-dir-link="<?= e($slug) ?>"><span><?= $n ?></span><?= e(t($d['title'])) ?></a>
      <?php endforeach; ?>
    </aside>
    <div class="sol__list">
      <?php $n = 0; foreach ($DIRECTIONS as $slug => $d): $n++; ?>
        <article class="sol-item" id="<?= e($slug) ?>" data-dir="<?= e($slug) ?>"<?= $n === 1 ? note('Каждая карточка «Подробнее» ведёт на своё направление на этой странице (сейчас все ведут на начало)') : '' ?>>
          <div class="sol-item__head">
            <span class="sol-item__ico"><?= icon($d['icon']) ?></span>
            <div>
              <span class="sol-item__num"><?= e(t('Направление')) ?> <?= $n ?></span>
              <h2><?= e(t($d['title'])) ?></h2>
            </div>
          </div>
          <p><?= e(t($d['text'])) ?></p>
          <ul class="checks">
            <?php foreach ($d['items'] as $it): ?><li><?= icon('check') ?><?= e(t($it)) ?></li><?php endforeach; ?>
          </ul>
          <?php if ($d['vendors']): ?>
            <div class="sol-item__vendors">
              <span class="muted"><?= e(t('Производители:')) ?></span>
              <?php foreach ($d['vendors'] as $v): ?>
                <a class="vendor" href="<?= url('partners/') ?>#<?= e($v) ?>"><?= e(t($PARTNERS[$v]['name'])) ?></a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <?php if (!empty($byDir[$slug])): ?>
            <div class="sol-item__projects">
              <?php foreach ($byDir[$slug] as $p): ?>
                <p><span class="tag"><?= e(t('Проект')) ?></span> <b><?= e(t($p['title'])) ?></b> — <?= e(t($p['solution'])) ?></p>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <div class="sol-item__cta">
            <button class="btn btn--accent btn--sm" type="button" data-lead data-direction="<?= e($slug) ?>"><?= e(t('Обсудить задачу')) ?></button>
            <a class="link" href="<?= url('news/') ?>"><?= e(t('Статьи по теме')) ?> →</a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php page_end();
