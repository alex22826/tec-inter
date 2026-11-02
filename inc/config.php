<?php
// Настройки демо-сайта TEC International.
// Код совместим с PHP 5.6 (сейчас на хостинге) и PHP 8.x.

return array(
    'site_name'   => 'TEC International',
    'site_url'    => 'http://tec-inter26.alexte1.beget.tech',
    'phone'       => '+7 (778) 975-63-35',
    'phone_href'  => '+77789756335',
    'email'       => 'info@tec-inter.kz',
    'hours'       => 'Пн-Пт, 9:00-18:00',
    'address'     => 'г. Астана, район Сарыарка, микрорайон Самал, здание 12',
    'servicedesk' => 'https://tecinternational.helpdeskeddy.com/',
    'founded'     => 2008,

    // Префикс всех адресов сайта: '' на хостинге, '/tec-inter' в статической копии на GitHub Pages
    'base_path'   => '',
    // Статическая копия (tools/render.php): адрес Pages, формы без отправки на сервер
    'static'      => false,
    'pages_origin' => 'https://alex22826.github.io',
    'pages_base'   => '/tec-inter',

    // Демо-режим: плашка «демо» сверху + noindex, чтобы тестовый домен
    // не попал в поиск и не конкурировал с tec-inter.kz.
    'demo'        => true,

    // Куда отправлять заявки. Пока пусто — заявки только пишутся в storage/leads.log.
    // На боевом сайте: почта info@tec-inter.kz + Telegram ответственного.
    'lead_mail_to'    => '',
    'lead_tg_token'   => '',
    'lead_tg_chat_id' => '',
);
