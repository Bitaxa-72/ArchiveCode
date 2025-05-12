<?php
$sbisLogin = 'логин';
$sbisPassword = 'пароль';

// Важно указать актуальнык ФИО аккаунта куда будут падать задачи с сайта
$responsiblePerson = [
    "Фамилия" => "сайт",
    "Имя" => "зявки",
    "Отчество" => "сбис"
];

$name = $_POST['fullname'] ?? '';
$phone = $_POST['tel'] ?? '';
$email = $_POST['email'] ?? '';
$comment = $_POST['txt'] ?? '';

if (empty($name) || empty($phone)) {
    return 'Укажите имя и телефон!';
}

$authPayload = [
    "jsonrpc" => "2.0",
    "method" => "СБИС.Аутентифицировать",
    "params" => [
        "Параметр" => [
            "Логин" => $sbisLogin,
            "Пароль" => $sbisPassword
        ]
    ],
    "id" => 1
];

$ch = curl_init('https://online.sbis.ru/auth/service/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=UTF-8']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($authPayload));
$authResponse = curl_exec($ch);
curl_close($ch);

$authData = json_decode($authResponse, true);
if (!isset($authData['result'])) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[SBIS Auth Error] ' . print_r($authData, true));
    return 'Ошибка авторизации в СБИС';
}
$sessionId = $authData['result'];

function generateUUID() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

$docId = generateUUID();
$attachId = generateUUID();
$date = date('d.m.Y');
$fileName = "Zayavka_{$docId}.doc";

$content = <<<HTML
<html xmlns:v="urn:schemas-microsoft-com:vml"
      xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:w="urn:schemas-microsoft-com:office:word"
      xmlns:m="http://schemas.microsoft.com/office/2004/12/omml"
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta charset="utf-8">
<title>Заявка с сайта</title>
</head>
<body>
<h1>Заявка с сайта</h1>
<p><strong>Имя:</strong> {$name}</p>
<p><strong>Телефон:</strong> {$phone}</p>
<p><strong>Комментарий:</strong><br>{$comment}</p>
</body>
</html>
HTML;

$docBase64 = base64_encode($content);

$documentPayload = [
    "jsonrpc" => "2.0",
    "method" => "СБИС.ЗаписатьДокумент",
    "params" => [
        "Документ" => [
            "Дата" => $date,
            "Номер" => "Заявка с сайта",
            "Идентификатор" => $docId,
            "Тип" => "СлужЗап",
            "Регламент" => [
                "Название" => "Задача"
            ],
            "Ответственный" => $responsiblePerson,
            "Вложение" => [
                [
                    "Идентификатор" => $attachId,
                    "Файл" => [
                        "Имя" => $fileName,
                        "ДвоичныеДанные" => $docBase64
                    ]
                ]
            ]
        ]
    ],
    "id" => 2
];

$ch = curl_init('https://online.sbis.ru/service/?srv=1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json; charset=UTF-8',
    'X-SBISSessionID: ' . $sessionId
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($documentPayload));
$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);
if (isset($result['error'])) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[SBIS Document Error] ' . print_r($result, true));
    return 'Ошибка при отправке документа в СБИС: ' . $result['error']['message'];
}

$modx->log(modX::LOG_LEVEL_INFO, '[SBIS Document Success] ' . print_r($result, true));
return 'Спасибо! Заявка успешно отправлена. ID: ' . $docId;
