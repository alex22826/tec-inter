<?php
$post = $POSTS[$SLUG];
$dir  = isset($DIRECTIONS[$post['dir']]) ? $DIRECTIONS[$post['dir']] : null;
$path = '/news/' . $SLUG . '/';
$isNews = $post['type'] === 'news';

// похожие: сначала того же направления, потом остальные
$similar = array();
foreach ($POSTS as $s => $p) {
    if ($s !== $SLUG && $p['dir'] === $post['dir']) $similar[$s] = $p;
}
foreach ($POSTS as $s => $p) {
    if (count($similar) >= 3) break;
    if ($s !== $SLUG && !isset($similar[$s])) $similar[$s] = $p;
}

$shareUrl = $CFG['site_url'] . $path;
page_start(array(
    'title' => $post['title'] . ' | TEC International',
    'description' => $post['excerpt'],
    'nav' => 'news',
    'path' => $path,
    'jsonld' => array(
        '@context' => 'https://schema.org',
        '@type' => $isNews ? 'NewsArticle' : 'Article',
        'headline' => $post['title'],
        'datePublished' => $post['date'],
        'author' => array('@type' => 'Organization', 'name' => 'TEC International'),
        'publisher' => array('@type' => 'Organization', 'name' => 'TEC International'),
    ),
));
?>
<section class="page-head page-head--article">
  <div class="container">
    <?= breadcrumbs(array('Новости и статьи' => url('news/'), $post['rubric'] => '')) ?>
  </div>
</section>

<div class="container article-wrap">
  <article class="article">
    <div class="post__meta"><span class="tag<?= $isNews ? ' tag--dark' : '' ?>"><?= $isNews ? 'Новость' : 'Статья' ?></span> <?= fmt_date($post['date']) ?> · <?= (int)$post['read'] ?> мин чтения · TEC International</div>
    <h1<?= note('H1 с поисковым запросом, дата, время чтения') ?>><?= e($post['title']) ?></h1>
    <?= cover($dir ? $dir['icon'] : 'docs', 'обложка', 'cover--article' . ($isNews ? ' cover--news' : '')) ?>
    <div class="prose"<?= note('Подзаголовки H2/H3, списки и таблицы — удобно читать и индексировать') ?>>
      <?= $post['body'] /* HTML из data.php, пишется вручную */ ?>
    </div>
    <div class="share"<?= note('Кнопки «Поделиться»: WhatsApp, Telegram, LinkedIn') ?>>
      <span class="muted">Поделиться:</span>
      <a href="https://wa.me/?text=<?= rawurlencode($post['title'] . ' ' . $shareUrl) ?>" target="_blank" rel="noopener">WhatsApp</a>
      <a href="https://t.me/share/url?url=<?= rawurlencode($shareUrl) ?>&amp;text=<?= rawurlencode($post['title']) ?>" target="_blank" rel="noopener">Telegram</a>
      <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= rawurlencode($shareUrl) ?>" target="_blank" rel="noopener">LinkedIn</a>
    </div>
  </article>

  <aside class="article-side">
    <div class="side-cta"<?= note('Блок заявки сбоку — статья приводит клиента') ?>>
      <h2><?= e($post['cta'][0]) ?></h2>
      <p><?= e($post['cta'][1]) ?></p>
      <button class="btn btn--accent" type="button" data-lead data-direction="<?= e($post['dir']) ?>">Получить консультацию</button>
    </div>
    <?php if ($dir): ?>
      <div class="side-box"<?= note('Связанное решение') ?>>
        <h2>Решение по теме</h2>
        <a class="link link--strong" href="<?= dir_link($post['dir']) ?>"><?= e($dir['title']) ?> →</a>
      </div>
    <?php endif; ?>
    <div class="side-box"<?= note('Похожие материалы') ?>>
      <h2>Похожие материалы</h2>
      <?php foreach ($similar as $s => $p): ?>
        <a class="side-link" href="<?= url('news/' . $s . '/') ?>"><?= e($p['title']) ?></a>
      <?php endforeach; ?>
    </div>
  </aside>
</div>
<?php page_end();
