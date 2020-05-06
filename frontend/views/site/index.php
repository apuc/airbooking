<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h2>Instructions</h2>
    </div>

    <div class="body-content">
        <div class="row">
            <p>
            <h4>Описание данных: <b>flight_id</b> - id рейса, <b>user_id</b> - id пользователя, <b>place</b> - номер места</h4><br>

            </p>
            <div class="col-lg-4">
                <p>
                <h3>Бронироание билета</h3>
                Экшн: <b>домен/api/v1/booking</b><br><br>
                Пример данных: <br><b>{"data":{"flight_id":1, "user_id":1, "place":1 }}</b><br><br>
                Тип запроса: <b>post</b><br>
                </p>
                <p>
                <h3>Покупка билета</h3>
                Экшн: <b>домен/api/v1/purchase</b> <br><br>
                Пример данных: <br><b>{"data":{"flight_id":1, "user_id":1, "place":1 }}</b> <br><br>
                Тип запроса: <b>post</b> <br>
                </p>
            </div>
            <div class="col-lg-4">
                <p>
                <h3>Отмена брони</h3>
                Экшн: <b>домен/api/v1/cancel-booking</b> <br><br>
                Пример данных: <br><b>{"data":{"flight_id":1, "user_id":1, "place":1 }}</b> <br><br>
                Тип запроса: <b>patch</b> <br>
                </p>
                <p>
                <h3>Отмена покупки</h3>
                Экшн: <b>домен/api/v1/cancel-purchase</b> <br><br>
                Пример данных: <br><b>{"data":{"flight_id":1, "user_id":1, "place":1 }}</b> <br><br>
                Тип запроса: <b>patch</b> <br>
                </p>
            </div>
            <div class="col-lg-4">
                <p>
                <h3>Отмена рейса</h3>
                Экшн: <b>домен/api/v1/cancel-flight</b> <br><br>
                Пример данных: <br><b>{"data":{"flight_id":1 }}</b> <br><br>
                Тип запроса: <b>patch</b> <br>
                </p>
                <p>
                <h3>Окончание продажи билетов</h3>
                Экшн: <b>домен/api/v1/completed-ticket-sales</b><br><br>
                Пример данных: <br><b>{"data":{"flight_id":1 }}</b><br><br>
                Тип запроса: <b>patch</b> <br>
                </p>
            </div>
        </div>

    </div>
</div>
