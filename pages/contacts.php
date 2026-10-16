<?php
page_start(array(
    'title' => 'Контакты — TEC International, Астана',
    'description' => 'Телефон +7 (778) 975-63-35, info@tec-inter.kz. Обсудить проект или получить консультацию по IT-инфраструктуре.',
    'nav' => 'contacts',
    'path' => '/contacts/',
));
?>
<section class="page-head">
  <div class="container">
    <?= breadcrumbs(array('Контакты' => '')) ?>
    <h1>Контакты</h1>
  </div>
</section>

<section class="section section--tight">
  <div class="container contacts">
    <div class="contacts__side">
      <div class="box"<?= note('Контакты одним блоком. Адрес указан только здесь — при переезде меняем в одном месте') ?>>
        <h2 class="h3">Связаться</h2>
        <ul class="contact-list">
          <li><?= icon('phone') ?><a class="strong" href="tel:<?= e($CFG['phone_href']) ?>"><?= e($CFG['phone']) ?></a></li>
          <li><?= icon('mail') ?><a href="mailto:<?= e($CFG['email']) ?>"><?= e($CFG['email']) ?></a></li>
          <li><?= icon('clock') ?><span><?= e($CFG['hours']) ?></span></li>
          <li><?= icon('pin') ?><span><?= e($CFG['address']) ?><br><a class="link" href="https://2gis.kz/astana/search/<?= rawurlencode('Астана, Самал, 12') ?>" target="_blank" rel="noopener">Открыть в 2ГИС →</a></span></li>
        </ul>
      </div>
      <div class="box"<?= note('Ссылка на Service Desk для действующих клиентов') ?>>
        <h2 class="h3">Техническая поддержка</h2>
        <p class="muted">Действующим клиентам — заявка в Service Desk, круглосуточно.</p>
        <a class="btn btn--outline btn--sm" href="<?= e($CFG['servicedesk']) ?>" target="_blank" rel="noopener">Service Desk 24/7 <?= icon('external') ?></a>
      </div>
    </div>
    <div class="box box--form"<?= note('Форма с полем «Направление», согласием на обработку данных и «Откуда узнали». Заявка — на почту и в Telegram, защита от спама') ?>>
      <h2 class="h3">Обсудить проект</h2>
      <?= lead_form('page', 'contacts') ?>
    </div>
  </div>
</section>
<?php page_end();
