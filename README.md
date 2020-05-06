<h4>Установка</h4>
1) Клонировать проект с репозитория https://github.com/apuc/airbooking
2) Находясь в папке проекта ыполнить команду: composer install
3) Затем выполнить команду php init
4) Создать БД и изменить данные подключения в common/config/main-local.php
5) Выполнить команду php yii migrate

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
          
       