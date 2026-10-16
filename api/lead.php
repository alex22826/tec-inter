<?php
// Приём заявок с форм сайта. Пишет в storage/leads.log (JSON по строке),
// а если заданы настройки в inc/config.php — шлёт на почту и в Telegram.

header('Content-Type: application/json; charset=utf-8');
header('X-Robots-Tag: noindex');

$CFG = require dirname(__DIR__) . '/inc/config.php';
require dirname(__DIR__) . '/inc/data.php';

function reply($code, $data)
{
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function field($name, $max = 500)
{
    $v = isset($_POST[$name]) ? trim((string)$_POST[$name]) : '';
    // управляющие символы, кроме перевода строки; на «битом» UTF-8 /u вернёт null — тогда без /u
    $clean = preg_replace('/[\x00-\x09\x0B-\x1F\x7F]/u', ' ', $v);
    $v = $clean === null ? preg_replace('/[\x00-\x09\x0B-\x1F\x7F]/', ' ', $v) : $clean;
    return function_exists('mb_substr') ? mb_substr($v, 0, $max, 'UTF-8') : substr($v, 0, $max);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    reply(405, array('ok' => false, 'error' => 'Метод не поддерживается'));
}

// Защита от спама: скрытое поле-ловушка и слишком быстрая отправка
$ts = (int)field('ts', 20);
if (field('website') !== '' || ($ts > 0 && time() - $ts < 3)) {
    reply(200, array('ok' => true)); // боту отвечаем «успешно», но ничего не сохраняем
}

$lead = array(
    'time'      => date('Y-m-d H:i:s'),
    'name'      => field('name', 100),
    'phone'     => field('phone', 40),
    'company'   => field('company', 150),
    'direction' => field('direction', 40),
    'message'   => field('message', 3000),
    'found'     => field('found', 100),
    'source'    => field('source', 40),
    'lang'      => field('lang', 5),
    'page'      => field('page', 300),
    'utm'       => field('utm', 500),
    'ip'        => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
);

$errors = array();
if ($lead['name'] === '') {
    $errors['name'] = 'Укажите имя';
}
if (strlen(preg_replace('/\D/', '', $lead['phone'])) < 10) {
    $errors['phone'] = 'Укажите телефон полностью';
}
if (field('consent') !== '1') {
    $errors['consent'] = 'Нужно согласие на обработку данных';
}
if ($errors) {
    reply(422, array('ok' => false, 'errors' => $errors));
}

$dirTitle = isset($DIRECTIONS[$lead['direction']]) ? $DIRECTIONS[$lead['direction']]['title'] : ($lead['direction'] === 'other' ? 'Другое' : '—');

$dir = dirname(__DIR__) . '/storage';
if (!is_dir($dir)) {
    @mkdir($dir, 0700, true);
}
$saved = @file_put_contents($dir . '/leads.log', json_encode($lead, JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND | LOCK_EX);

$text = "Заявка с сайта TEC\n"
    . "Имя: {$lead['name']}\nТелефон: {$lead['phone']}\n"
    . "Компания: " . ($lead['company'] ?: '—') . "\nНаправление: {$dirTitle}\n"
    . ($lead['message'] !== '' ? "Задача: {$lead['message']}\n" : '')
    . ($lead['found'] !== '' ? "Откуда узнали: {$lead['found']}\n" : '')
    . "Форма: {$lead['source']} · {$lead['page']} · язык " . strtoupper($lead['lang'] ?: 'ru') . "\n"
    . ($lead['utm'] !== '' ? "UTM: {$lead['utm']}\n" : '');

if ($CFG['lead_mail_to'] !== '') {
    $subject = '=?UTF-8?B?' . base64_encode('Заявка с сайта: ' . $lead['name']) . '?=';
    $headers = "Content-Type: text/plain; charset=utf-8\r\nFrom: TEC site <noreply@" . $_SERVER['HTTP_HOST'] . ">";
    @mail($CFG['lead_mail_to'], $subject, $text, $headers);
}

if ($CFG['lead_tg_token'] !== '' && $CFG['lead_tg_chat_id'] !== '' && function_exists('curl_init')) {
    $ch = curl_init('https://api.telegram.org/bot' . $CFG['lead_tg_token'] . '/sendMessage');
    curl_setopt_array($ch, array(
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => array('chat_id' => $CFG['lead_tg_chat_id'], 'text' => $text),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 8,
    ));
    curl_exec($ch);
    curl_close($ch);
}

if ($saved === false && $CFG['lead_mail_to'] === '') {
    reply(500, array('ok' => false, 'error' => 'Не удалось сохранить заявку, позвоните нам'));
}
reply(200, array('ok' => true));
