<?php
page_start(array(
    'title' => 'Решения — 10 направлений IT-интеграции | TEC International',
    'description' => 'Сети, серверы и СХД, ЦОД, информационная безопасность, ПО, виртуализация и бэкап, СКУД и видеонаблюдение, рабочие места, спецсистемы, сервис 24/7.',
    'nav' => 'solutions',
    'path' => '/solutions/',
));

// проекты по направлениям — для блока «Проекты по направлению»
$byDir = array();
foreach ($PROJECTS as $p) {
    $byDir[$p['dir']][] = $p;
}
?>
<section class="page-head">
  <div class="container">
    <?= breadcrumbs(array('Решения' => '')) ?>
    <h1>Решения</h1>
    <p class="lead">10 направлений — один подрядчик. Подбираем, поставляем, внедряем и сопровождаем.</p>
  </div>
</section>

<section class="section section--tight" id="picker"<?= note('«Подобрать решение»: выбор отрасли подсвечивает подходящие направления') ?>>
  <div class="container">
    <div class="picker">
      <div class="picker__head">
        <h2>Подобрать решение</h2>
        <p class="muted">Выберите отрасль — подсветим направления, которые обычно нужны.</p>
      </div>
      <div class="chips" role="group" aria-label="Отрасль">
        <?php foreach ($INDUSTRIES as $slug => $ind): ?>
          <button class="chip" type="button" data-industry="<?= e($slug) ?>" data-dirs="<?= e(implode(',', $ind['dirs'])) ?>" aria-pressed="false"><?= e($ind['title']) ?></button>
        <?php endforeach; ?>
      </div>
      <div class="picker__result" hidden>
        <p class="picker__text"></p>
        <button class="btn btn--accent btn--sm" type="button" data-lead data-lead-industry="">Обсудить задачу</button>
        <button class="btn btn--outline btn--sm" type="button" data-picker-reset>Сбросить</button>
      </div>
    </div>
  </div>
</section>

<section class="section section--tight">
  <div class="container sol">
    <aside class="sol__nav" aria-label="Направления">
      <?php $n = 0; foreach ($DIRECTIONS as $slug => $d): $n++; ?>
        <a href="#<?= e($slug) ?>" data-dir-link="<?= e($slug) ?>"><span><?= $n ?></span><?= e($d['title']) ?></a>
      <?php endforeach; ?>
    </aside>
    <div class="sol__list">
      <?php $n = 0; foreach ($DIRECTIONS as $slug => $d): $n++; ?>
        <article class="sol-item" id="<?= e($slug) ?>" data-dir="<?= e($slug) ?>"<?= $n === 1 ? note('Каждая карточка «Подробнее» ведёт на своё направление на этой странице (сейчас все ведут на начало)') : '' ?>>
          <div class="sol-item__head">
            <span class="sol-item__ico"><?= icon($d['icon']) ?></span>
            <div>
              <span class="sol-item__num">Направление <?= $n ?></span>
              <h2><?= e($d['title']) ?></h2>
            </div>
          </div>
          <p><?= e($d['text']) ?></p>
          <ul class="checks">
            <?php foreach ($d['items'] as $it): ?><li><?= icon('check') ?><?= e($it) ?></li><?php endforeach; ?>
          </ul>
          <?php if ($d['vendors']): ?>
            <div class="sol-item__vendors">
              <span class="muted">Производители:</span>
              <?php foreach ($d['vendors'] as $v): ?>
                <a class="vendor" href="<?= url('partners/') ?>#<?= e($v) ?>"><?= e($PARTNERS[$v]['name']) ?></a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <?php if (!empty($byDir[$slug])): ?>
            <div class="sol-item__projects">
              <?php foreach ($byDir[$slug] as $p): ?>
                <p><span class="tag">Проект</span> <b><?= e($p['title']) ?></b> — <?= e($p['solution']) ?></p>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <div class="sol-item__cta">
            <button class="btn btn--accent btn--sm" type="button" data-lead data-direction="<?= e($slug) ?>">Обсудить задачу</button>
            <a class="link" href="<?= url('news/') ?>">Статьи по теме →</a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php page_end();
