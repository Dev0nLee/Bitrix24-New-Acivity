<?php

declare(strict_types=1);


require_once 'vendor/autoload.php';

?>
<pre>
    Application installation has been started, auth-tokens from Bitrix24:
    <?= print_r($_REQUEST, true) ?>
</pre>
<script src="//api.bitrix24.com/api/v1/"></script>
<script>
    BX24.init(function(){
        BX24.installFinish();
    });
</script>
<script type="text/javascript">
    var params = {
    'CODE': 'pause_action',
    'HANDLER': 'https://grudgingly-plentiful-crow.cloudpub.ru/pause_handler.php', //указать ссылку на свой хост
    'AUTH_USER_ID': 1,
    'USE_SUBSCRIPTION': 'Y',
    'NAME': {
    'ru': 'Пауза',
    'en': 'Pause'
    },
    'DESCRIPTION': {
    'ru': 'Действие ставит паузу в бизнес процессе',
    'en': 'Activity pauses in proccess'
    },
    'PROPERTIES': {
    'pause_value': {
    'Name': {
    'ru': 'Значение паузы',
    'en': 'Pause value'
    },
    'Description': {
    'ru': 'Введите значение паузы',
    'en': 'Enter pause value'
    },
    'Type': 'int',
    'Required': 'Y',
    'Multiple': 'N',
    'Default': 1,
    },
    'pause_type' : {
        'Name' : {
            'ru': 'Тип паузы (минуты/секунды)',
            'en': 'Type pause (minutes/seconds)'
        },
        'Description' : {
            'ru': 'Выберите тип паузы',
            'en': 'Select pause type'
        },
        'Type': 'select',
        'Required': 'Y',
        'Multiple': 'N',
        'Options': {
            'seconds' : {
                'ru' : 'секунды',
                'en' : 'seconds'
            },
            'minutes' : {
                'ru' : 'минуты',
                'en' : 'minutes'
            }
        },
        'Default' : 'seconds'

    }
    },
    'RETURN_PROPERTIES': {
        'select_seconds' : {
            'Name' : {
                'ru' : 'Выбрано секунд',
                'en' : 'Select seconds'
            },
            'Type' : 'int',
            'Multiple' : 'N',
            'Default' : 0
        }
    },
    };
    
    BX24.callMethod(
    'bizproc.activity.add',
    params,
    function(result)
    {
    if(result.error())
    alert("Error: " + result.error());
    else
    alert("Успешно: " + result.data());
    }
    );
</script>
