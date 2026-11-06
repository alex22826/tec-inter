<?php
page_start(array(
    'title' => t('Контакты | TEC International, Астана'),
    'description' => t('Телефон +7 (778) 975-63-35, info@tec-inter.kz. Обсудить проект или получить консультацию по IT-инфраструктуре.'),
    'nav' => 'contacts',
    'art' => 'vpn',
));
?>
<section class="page-head">
  <div class="container">
    <?= breadcrumbs(array(t('Контакты') => '')) ?>
    <h1><?= e(t('Контакты')) ?></h1>
  </div>
</section>

<section class="section section--tight">
  <div class="container contacts">
    <div class="contacts__side">
      <div class="box"<?= note('Контакты одним блоком. Адрес указан только здесь, при переезде меняем его в одном месте') ?>>
        <h2 class="h3"><?= e(t('Связаться')) ?></h2>
        <ul class="contact-list">
          <li><?= icon('phone') ?><a class="strong" href="tel:<?= e($CFG['phone_href']) ?>"><?= e($CFG['phone']) ?></a></li>
          <li><?= icon('mail') ?><a href="mailto:<?= e($CFG['email']) ?>"><?= e($CFG['email']) ?></a></li>
          <li><?= icon('clock') ?><span><?= e(t($CFG['hours'])) ?></span></li>
          <li><?= icon('pin') ?><span><?= e(t($CFG['address'])) ?><br><a class="link" href="https://2gis.kz/astana/search/<?= rawurlencode('Астана, Самал, 12') ?>" target="_blank" rel="noopener"><?= e(t('Открыть в 2ГИС')) ?> →</a></span></li>
        </ul>
      </div>
      <div class="box"<?= note('Ссылка на Service Desk для действующих клиентов') ?>>
        <h2 class="h3"><?= e(t('Техническая поддержка')) ?></h2>
        <p class="muted"><?= e(t('Действующие клиенты могут оставить заявку в Service Desk круглосуточно.')) ?></p>
        <a class="btn btn--outline btn--sm" href="<?= e($CFG['servicedesk']) ?>" target="_blank" rel="noopener">Service Desk 24/7 <?= icon('external') ?></a>
      </div>
    </div>
    <div class="box box--form"<?= note('Форма с полем «Направление», согласием на обработку данных и «Откуда узнали». Заявка уходит на почту и в Telegram, есть защита от спама') ?>>
      <h2 class="h3"><?= e(t('Обсудить проект')) ?></h2>
      <?= lead_form('page', 'contacts') ?>
    </div>
  </div>
</section>
<?php page_end();
