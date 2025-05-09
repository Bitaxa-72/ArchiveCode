<?php
$name = $hook->getValue('fullname');
$phone = $hook->getValue('tel');
$email = $hook->getValue('email');
$message = $hook->getValue('txt');

$data = [
    'name' => $name,
    'phone' => $phone,
    'email' => $email,
    'message' => $message
];

$url = 'https://online.sbis.ru/lid/handler/endpoint'; 

// Отправка через cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',

]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode != 200) {
    $hook->addError('sbis', 'Ошибка отправки данных в СБИС');
    return false;
}

return true;