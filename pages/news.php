<?php
page_start(array(
    'title' => 'Новости и статьи об IT-инфраструктуре | TEC International',
    'description' => 'Новости TEC International и экспертные статьи: сети, серверы, информационная безопасность, виртуализация, госзакупки.',
    'nav' => 'news',
    'path' => '/news/',
));
$rubrics = array();
$inds = array();
foreach ($POSTS as $p) {
    $rubrics[$p['rubric']] = true;
    if ($p['industry']) {
        $inds[$p['industry']] = $INDUSTRIES[$p['industry']]['title'];
    }
}
?>
<section class="page-head">
  <div class="container">
    <?= breadcrumbs(array('Новости и статьи' => '')) ?>
    <h1>Новости и статьи</h1>
  </div>
</section>

<section class="section section--tight">
  <div class="container">
    <div class="filters">
      <div class="chips filter" data-filter="type" role="group" aria-label="Тип"<?= note('Вкладки: Все / Новости / Статьи') ?>>
        <button class="chip is-active" type="button" data-value="" aria-pressed="true">Все</button>
        <button class="chip" type="button" data-value="news" aria-pressed="false">Новости</button>
        <button class="chip" type="button" data-value="article" aria-pressed="false">Статьи</button>
      </div>
      <div class="selects"<?= note('Фильтр по рубрике и отрасли') ?>>
        <select class="select filter-select" data-filter="rubric" aria-label="Рубрика">
          <option value="">Рубрика: все</option>
          <?php foreach (array_keys($rubrics) as $r): ?><option value="<?= e($r) ?>"><?= e($r) ?></option><?php endforeach; ?>
        </select>
        <select class="select filter-select" data-filter="industry" aria-label="Отрасль">
          <option value="">Отрасль: все</option>
          <?php foreach ($inds as $slug => $t): ?><option value="<?= e($slug) ?>"><?= e($t) ?></option><?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="grid-3" data-filter-list<?= note('Карточки: обложка, тип, рубрика, дата, заголовок') ?>>
      <?php foreach ($POSTS as $slug => $p) echo post_card($slug, $p); ?>
    </div>
    <p class="empty" hidden>По выбранным фильтрам материалов пока нет.</p>
    <p class="footnote">* Новости — коротко о проектах, статусах, мероприятиях. Статьи — экспертиза под поисковые запросы клиентов. На старте — минимум 5 материалов.</p>
  </div>
</section>
<?php page_end();
