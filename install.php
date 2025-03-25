<?php

declare(strict_types=1);

use Bitrix24\SDK\Core\Credentials\ApplicationProfile;
use Bitrix24\SDK\Services\ServiceBuilderFactory;
use Symfony\Component\HttpFoundation\Request;

require_once 'vendor/autoload.php';

$appProfile = ApplicationProfile::initFromArray([
    'BITRIX24_PHP_SDK_APPLICATION_CLIENT_ID' => 'local.67e29f9bd39222.73091908',
    'BITRIX24_PHP_SDK_APPLICATION_CLIENT_SECRET' => 'ebUppZw6yuqIbqfsG472mTKHo6BRT5rl6eLAEX0qCIVVGXzVkI',
    'BITRIX24_PHP_SDK_APPLICATION_SCOPE' => 'crm,user_basic,placement,bizproc'
]);

$B24 = ServiceBuilderFactory::createServiceBuilderFromPlacementRequest(
    Request::createFromGlobals(), 
    $appProfile
);

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
        'pause_type' => [
            'Name' => [
                'ru' => 'Тип паузы (минуты/секунды)',
                'en' => 'Type pause (minutes/seconds)'
            ],
            'Description' => [
                'ru' => 'Выберите тип паузы',
                'en' => 'Select pause type'
            ],
            'Type' => 'select',
            'Required' => 'Y',
            'Multiple' => 'N',
            'Options' => [
                'seconds' => [
                    'ru' => 'секунды',
                    'en' => 'seconds'
                ],
                'minutes' => [
                    'ru' => 'минуты',
                    'en' => 'minutes'
                ]
            ],
            'Default' => 'seconds'
        ]
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

try {
    $response = $B24->core->call('bizproc.activity.add', $activityParams);
    
    $result = $response->getResponseData()->getResult();
    echo "Действие успешно добавлено.";
} catch (\Exception $e) {
    echo "Ошибка при добавлении действия: " . $e->getMessage();
}
?>
