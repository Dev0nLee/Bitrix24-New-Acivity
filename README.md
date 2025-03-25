# Bitrix24-New-Acivity
Add new pause activity in Business processes

Установка:
1. Склонировать репозиторий
   `git clone https://github.com/Dev0nLee/Bitrix24-New-Acivity.git`
2. Перейти в папку проекта
   `cd Bitrix24-New-Acivity`
3. Залить на сервер
4. Поменять на свой хост в файле `install.php`
5. При добавлении локального приложения в Bitrix24 указать путь обработчика - путь на сервере до `pause_handler.php`, а путь первоначальной установки - `install.php`. Поменять client_id и client_secret в `settings.php` на свои. После установки приложения должен сгенерироваться файл `settings.json` 
