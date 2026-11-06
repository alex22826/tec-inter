<?php
page_start(array(
    'title' => t('Страница не найдена | TEC International'),
    'description' => t('Страница не найдена.'),
    'nav' => '',
    'art' => 'servers',
));
?>
<section class="section notfound">
  <div class="container">
    <p class="notfound__code">404</p>
    <h1><?= e(t('Страница не найдена')) ?></h1>
    <p class="lead"><?= e(t('Возможно, адрес изменился после обновления сайта.')) ?></p>
    <div class="hero__btns">
      <a class="btn btn--accent" href="<?= url() ?>"><?= e(t('На главную')) ?></a>
      <a class="btn btn--outline" href="<?= url('solutions/') ?>"><?= e(t('Решения')) ?></a>
    </div>
  </div>
</section>
<?php page_end();
