<?php

namespace frontend\modules\api\controllers;

use common\models\Flight;
use common\models\NotificationQueue;
use common\models\Ticket;
use common\models\User;
use DateTime;
use PHPMailer\PHPMailer\PHPMailer;
use Yii;
use yii\rest\Controller;


class V1Controller extends Controller
{
    public function actionBooking()
    {
        if( Yii::$app->request->isPost) {
            $data = json_decode(Yii::$app->request->getRawBody());
            if($data->data->flight_id && $data->data->user_id && $data->data->place) {
                $flight_status = Flight::checkFlight($data->data->flight_id);
                if($flight_status == 0) {
                    $ticket = Ticket::findOne(['flight_id' => $data->data->flight_id, 'place' => $data->data->place]);
                    if(!isset($ticket))
                        $ticket = new Ticket();

                    return $ticket->bookingTicket($data->data->flight_id, $data->data->user_id, $data->data->place);
                } else
                    return Flight::flightStatus($flight_status);
            } else
                return json_encode(['error' => 'Не введены необходимые данные']);
        }
        else {
            return json_encode(['error' => 'Неправильный метод отправки данных. Ожидается POST.']);
        }
    }

    public function actionCancelBooking()
    {
        if( Yii::$app->request->isPatch) {
            $data = json_decode(Yii::$app->request->getRawBody());
            if($data->data->flight_id && $data->data->user_id && $data->data->place) {
                $flight_status = Flight::checkFlight($data->data->flight_id);
                if($flight_status == 0) {
                    $ticket = Ticket::findOne(['flight_id' => $data->data->flight_id, 'user_id' => $data->data->user_id,
                        'place' => $data->data->place]);
                    if(isset($ticket))
                        return $ticket->cancelBookingTicket($data->data->flight_id, $data->data->user_id, $data->data->place);
                    else
                        return json_encode(['error' => 'Вы не бронировали этот билет']);
                } else return Flight::flightStatus($flight_status);
            } else
                return json_encode(['error' => 'Не введены необходимые данные']);
        }
        else {
            return json_encode(['error' => 'Неправильный метод отправки данных. Ожидается PATCH.']);
        }
    }

    public function actionPurchase()
    {
        if( Yii::$app->request->isPost) {
            $data = json_decode(Yii::$app->request->getRawBody());
            if($data->data->flight_id && $data->data->user_id && $data->data->place) {
                $flight_status = Flight::checkFlight($data->data->flight_id);
                if($flight_status == 0) {
                    $ticket = Ticket::findOne(['flight_id' => $data->data->flight_id, 'place' => $data->data->place]);
                    if(!isset($ticket))
                        $ticket = new Ticket();

                    return $ticket->purchaseTicket($data->data->flight_id, $data->data->user_id, $data->data->place);
                } else
                    return Flight::flightStatus($flight_status);
            } else
                return json_encode(['error' => 'Не введены необходимые данные']);
        }
        else {
            return json_encode(['error' => 'Неправильный метод отправки данных. Ожидается POST.']);
        }
    }

    public function actionCancelPurchase()
    {
        if( Yii::$app->request->isPatch) {
            $data = json_decode(Yii::$app->request->getRawBody());
            if($data->data->flight_id && $data->data->user_id && $data->data->place) {
                $flight_status = Flight::checkFlight($data->data->flight_id);
                if($flight_status == 0) {
                    $ticket = Ticket::findOne(['flight_id' => $data->data->flight_id, 'user_id' => $data->data->user_id,
                        'place' => $data->data->place]);
                    if(isset($ticket))
                        return $ticket->cancelPurchaseTicket($data->data->flight_id, $data->data->user_id, $data->data->place);
                    else
                        return json_encode(['error' => 'Вы не покупали этот билет']);
                } else return Flight::flightStatus($flight_status);
            } else
                return json_encode(['error' => 'Не введены необходимые данные']);
        }
        else {
            return json_encode(['error' => 'Неправильный метод отправки данных. Ожидается PATCH.']);
        }
    }

    public function actionCancelFlight()
    {
        if( Yii::$app->request->isPatch) {
            $data = json_decode(Yii::$app->request->getRawBody());
            try {
                $flight = Flight::findOne($data->data->flight_id);
                $flight->status = 3;
                $flight->save();

                $tickets = Ticket::find()->where(['>', 'status', 0])->andWhere(['flight_id' => $data->data->flight_id])->all();
                foreach ($tickets as $ticket) {
                    $nf = new NotificationQueue();
                    $nf->user_id = $ticket->user_id;
                    $nf->flight_id = $ticket->flight_id;
                    $nf->notification_type = 3;
                    $nf->save();
                }

                return json_encode(['success' => 'Рейс успешно отменен.']);
            } catch (\Exception $e) {
                return json_encode(['error' => 'Такого рейса не существует.']);
            }
        }
    }

    public function actionFlightCanceled()
    {
        $nq = NotificationQueue::find()->all();
        while($nq) {
            foreach ($nq as $item) {
                $user = User::findOne($item->user_id);
                $flight = Flight::findOne($item->flight_id);
                echo self::send_mail($user->email, $flight->name);
                NotificationQueue::deleteAll(['user_id' => $item->user_id, 'flight_id' => $item->flight_id]);
                break;
            }
            $nq = NotificationQueue::find()->all();
        }
    }

    public function actionCompletedTicketSales()
    {
        if( Yii::$app->request->isPatch) {
            $data = json_decode(Yii::$app->request->getRawBody());
            try {
                $flight = Flight::findOne($data->data->flight_id);
                $flight->status = 2;
                $flight->save();

                return json_encode(['success' => 'Продажа билетов остановлена.']);
            } catch (\Exception $e) {
                return json_encode(['error' => 'Такого рейса не существует.']);
            }
        }
    }

    public static function send_mail($email, $flight)
    {
        $mail = new PHPMailer;
        $mail->CharSet = 'utf-8';

        $body = str_replace('{FLIGHT}', $flight, Yii::$app->params['message_body']);

        $mail->isSMTP();
        $mail->Host = 'smtp.mail.ru';
        $mail->SMTPAuth = true;
        $mail->Username = Yii::$app->params['notification_email'];
        $mail->Password = Yii::$app->params['password'];
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom(Yii::$app->params['notification_email']);
        $mail->addAddress($email);
        $mail->isHTML(true);

        $mail->Subject = Yii::$app->params['message_title'];
        $mail->Body = $body;
        $mail->AltBody = '';

        if (!$mail->send()) {
            return json_encode(['error' => 'Не удалось отправить уведомление.']);
        } else {
            return json_encode(['success' => 'Уведомление отправлено успешно.']);
        }
    }
}
