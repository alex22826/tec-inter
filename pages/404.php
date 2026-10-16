<?php
page_start(array(
    'title' => 'Страница не найдена | TEC International',
    'description' => 'Страница не найдена.',
    'nav' => '',
    'path' => '/',
));
?>
<section class="section notfound">
  <div class="container">
    <p class="notfound__code">404</p>
    <h1>Страница не найдена</h1>
    <p class="lead">Возможно, адрес изменился после обновления сайта.</p>
    <div class="hero__btns">
      <a class="btn btn--accent" href="<?= url() ?>">На главную</a>
      <a class="btn btn--outline" href="<?= url('solutions/') ?>">Решения</a>
    </div>
  </div>
</section>
<?php page_end();
