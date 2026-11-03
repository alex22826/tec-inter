<?php
page_start(array(
    'title' => t('Новости и статьи об IT-инфраструктуре | TEC International'),
    'description' => t('Новости TEC International и экспертные статьи: сети, серверы, информационная безопасность, виртуализация, госзакупки.'),
    'nav' => 'news',
));
$rubrics = array();
$inds = array();
$types = array(); // подгруппа «Новости» видна, только когда есть хотя бы одна новость
foreach ($POSTS as $p) {
    $types[$p['type']] = true;
    $rubrics[$p['rubric']] = true;
    if ($p['industry']) {
        $inds[$p['industry']] = $INDUSTRIES[$p['industry']]['title'];
    }
}
?>
<section class="page-head">
  <div class="container">
    <?= breadcrumbs(array(t('Новости и статьи') => '')) ?>
    <h1><?= e(t('Новости и статьи')) ?></h1>
  </div>
</section>

<section class="section section--tight">
  <div class="container">
    <div class="filters">
      <div class="chips filter" data-filter="type" role="group" aria-label="<?= e(t('Тип')) ?>"<?= note('Вкладки: Все / Новости / Статьи. «Новости» появятся сами с первой новостью компании') ?>>
        <button class="chip is-active" type="button" data-value="" aria-pressed="true"><?= e(t('Все')) ?></button>
        <?php if (!empty($types['news'])): ?><button class="chip" type="button" data-value="news" aria-pressed="false"><?= e(t('Новости')) ?></button><?php endif; ?>
        <?php if (!empty($types['article'])): ?><button class="chip" type="button" data-value="article" aria-pressed="false"><?= e(t('Статьи')) ?></button><?php endif; ?>
      </div>
      <div class="selects"<?= note('Фильтр по рубрике и отрасли') ?>>
        <select class="select filter-select" data-filter="rubric" aria-label="<?= e(t('Рубрика')) ?>">
          <option value=""><?= e(t('Рубрика: все')) ?></option>
          <?php foreach (array_keys($rubrics) as $r): ?><option value="<?= e($r) ?>"><?= e(t($r)) ?></option><?php endforeach; ?>
        </select>
        <select class="select filter-select" data-filter="industry" aria-label="<?= e(t('Отрасль')) ?>">
          <option value=""><?= e(t('Отрасль: все')) ?></option>
          <?php foreach ($inds as $slug => $title): ?><option value="<?= e($slug) ?>"><?= e(t($title)) ?></option><?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="grid-3" data-filter-list<?= note('Карточки: обложка, тип, рубрика, дата, заголовок') ?>>
      <?php foreach ($POSTS as $slug => $p) echo post_card($slug, $p); ?>
    </div>
    <p class="empty" hidden><?= e(t('По выбранным фильтрам материалов пока нет.')) ?></p>
    <p class="footnote"><?= e(t('* Новости: коротко о проектах, статусах, мероприятиях. Статьи: экспертиза под поисковые запросы клиентов. На старте нужно минимум 5 материалов.')) ?></p>
  </div>
</section>
<?php page_end();
