<?php

namespace console\controllers;

use common\models\Flight;
use common\models\NotificationQueue;
use common\models\User;
use PHPMailer\PHPMailer\PHPMailer;
use Yii;
use yii\console\Controller;

class FlightController extends Controller
{
    public function actionCancel()
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