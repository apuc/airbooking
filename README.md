<h4>Установка</h4>
1) Клонировать проект с репозитория https://github.com/apuc/airbooking
2) Находясь в папке проекта ыполнить команду: <b>composer install</b>
3) Затем выполнить команду <b>php init</b>
4) Создать БД и изменить данные подключения в <b>common/config/main-local.php</b>
5) Выполнить команду <b>php yii migrate</b>
6) В файле console/config/params.php нужно настроить данные для отправки уведомлений на почту
7) Настроить cron для автоматической отправки уведомлений об отмене рейса.<br>
Для этого выполнить команду: <b>crontab -e</b><br>
В открывшемся файле добавить строку:<br>
<b> */60 * * * * /var/www/domains/airbooking/yii flight/cancel /dev/null 2>&1 </b>, где <br>
60 - частота выполнения команды в минутах<br>
/var/www/domains/airbooking/ - путь к папке домена<br>

<h4>Инструкция к Api</h4>
1) Бронироание билета <br>
   Экшн: <b>домен/api/v1/booking</b><br>
   Пример данных: <b>{"data":{"flight_id":1, "user_id":1, "place":1 }}</b><br>
   Тип запроса: <b>post</b><br><br>
2) Покупка билета <br>
   Экшн: <b>домен/api/v1/purchase</b><br>
   Пример данных: <b>{"data":{"flight_id":1, "user_id":1, "place":1 }}</b><br>
   Тип запроса: <b>post</b><br><br>
3) Отмена брони <br>
   Экшн: <b>домен/api/v1/cancel-booking</b><br>
   Пример данных: <b>{"data":{"flight_id":1, "user_id":1, "place":1 }}</b><br>
   Тип запроса: <b>patch</b><br><br>
4) Отмена покупки <br>
   Экшн: <b>домен/api/v1/cancel-purchase</b><br>
   Пример данных: <b>{"data":{"flight_id":1, "user_id":1, "place":1 }}</b><br>
   Тип запроса: <b>patch</b><br><br>
5) Отмена рейса <br>
   Экшн: <b>домен/api/v1/cancel-flight</b><br>
   Пример данных: <b>{"data":{"flight_id":1 }}</b><br>
   Тип запроса: <b>patch</b><br><br>
6) Окончание продажи билетов <br>
   Экшн: <b>домен/api/v1/completed-ticket-sales</b><br>
   Пример данных: <b>{"data":{"flight_id":1 }}</b><br>
   Тип запроса: <b>patch</b><br><br>
               
<h3>Описание данных</h3><br>
   <b>flight_id</b> - id рейса <br>
   <b>user_id</b> - id пользователя <br>
   <b>place</b> - номер места <br>