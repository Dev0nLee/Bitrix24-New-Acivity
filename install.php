<?php

declare(strict_types=1);

require_once __DIR__ . '/crest.php';

$result = CRest::installApp();
header('Content-Type: application/json');
echo json_encode($result);

$activityParams = [
    'CODE' => 'pause_action',
    'HANDLER' => 'https://grudgingly-plentiful-crow.cloudpub.ru/pause_handler.php',
    'AUTH_USER_ID' => 1,
    'USE_SUBSCRIPTION' => 'Y',
    'NAME' => [
        'ru' => 'Пауза',
        'en' => 'Pause'
    ],
    'DESCRIPTION' => [
        'ru' => 'Действие ставит паузу в бизнес процессе',
        'en' => 'Activity pauses in proccess'
    ],
    'PROPERTIES' => [
        'pause_value' => [
            'Name' => [
                'ru' => 'Значение паузы',
                'en' => 'Pause value'
            ],
            'Description' => [
                'ru' => 'Введите значение паузы',
                'en' => 'Enter pause value'
            ],
            'Type' => 'int',
            'Required' => 'Y',
            'Multiple' => 'N',
            'Default' => 1
        ],
    ],
    'RETURN_PROPERTIES' => [
        'select_seconds' => [
            'Name' => [
                'ru' => 'Выбрано секунд',
                'en' => 'Select seconds'
            ],
            'Type' => 'int',
            'Multiple' => 'N',
            'Default' => 0
        ]
    ]
];

$result = CRest::call(
    'bizproc.activity.add',
    $activityParams);
    echo "Действие успешно добавлено.";

?>
