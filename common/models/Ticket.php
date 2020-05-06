<?php

namespace common\models;


/**
 * This is the model class for table "ticket".
 *
 * @property int $id
 * @property int|null $place
 * @property float|null $price
 * @property int|null $status
 * @property string $purchase_datetime
 * @property int $flight_id
 * @property int $user_id
 *
 * @property Flight $flight
 * @property User $user
 */
class Ticket extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ticket';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['place', 'status', 'flight_id', 'user_id'], 'integer'],
            [['price'], 'number'],
            [['purchase_datetime'], 'safe'],
            [['flight_id', 'user_id'], 'required'],
            [['flight_id'], 'exist', 'skipOnError' => true, 'targetClass' => Flight::className(), 'targetAttribute' => ['flight_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'place' => 'Place',
            'price' => 'Price',
            'status' => 'Status',
            'purchase_datetime' => 'Purchase Datetime',
            'flight_id' => 'Flight ID',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[Flight]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFlight()
    {
        return $this->hasOne(Flight::className(), ['id' => 'flight_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function bookingTicket($flight_id, $user_id, $place)
    {
        if(!$this->status || $this->status == 0) {
            $this->saveData($flight_id, $user_id, $place, 1);
            $this->flightStatus($flight_id);

            return json_encode(['success' => 'Билет успешно забронирован']);
        }
        elseif($this->status == 1 && $this->user_id == $user_id)
            return json_encode(['error' => 'Вы уже забронировали этот билет']);
        elseif($this->status == 1 && $this->user_id != $user_id)
            return json_encode(['error' => 'Этот билет уже забронирован другим пользователем']);
        elseif($this->status == 2 && $this->user_id == $user_id)
            return json_encode(['error' => 'Вы уже купили этот билет']);
        elseif($this->status == 2 && $this->user_id != $user_id)
            return json_encode(['error' => 'Этот билет уже куплен другим пользователем']);
        else
            return json_encode(['error' => 'Неизвестная ошибка']);
    }

    public function purchaseTicket($flight_id, $user_id, $place)
    {
        if(!$this->status || $this->status == 0 || ($this->status == 1 && $this->user_id == $user_id)) {
            $this->saveData($flight_id, $user_id, $place, 2);
            $this->flightStatus($flight_id);

            return json_encode(['success' => 'Билет успешно куплен']);
        }
        elseif($this->status == 2 && $this->user_id == $user_id)
            return json_encode(['error' => 'Вы уже купили этот билет']);
        elseif($this->status == 1 && $this->user_id != $user_id)
            return json_encode(['error' => 'Этот билет уже забронирован другим пользователем']);
        elseif($this->status == 2 && $this->user_id != $user_id)
            return json_encode(['error' => 'Этот билет уже куплен другим пользователем']);
        else
            return json_encode(['error' => 'Неизвестная ошибка']);
    }

    public function cancelBookingTicket($flight_id, $user_id, $place)
    {
        if($this->status == 1) {
            $this->saveData($flight_id, $user_id, $place, 0);
            $this->flightStatus($flight_id);

            return json_encode(['success' => 'Бронь успешно снята']);
        }
        elseif($this->status == 2)
            return json_encode(['error' => 'Вы купили этот билет, воспользуйтесь функцией отмены покупки']);
        else
            return json_encode(['error' => 'Неизвестная ошибка']);
    }

    public function cancelPurchaseTicket($flight_id, $user_id, $place)
    {
        if($this->status == 2) {
            $this->saveData($flight_id, $user_id, $place, 0);
            $this->flightStatus($flight_id);

            return json_encode(['success' => 'Покупка успешно отменена']);
        }
        elseif($this->status == 1)
            return json_encode(['error' => 'Вы забронировали этот билет, воспользуйтесь функцией отмены бронирования']);
        else
            return json_encode(['error' => 'Неизвестная ошибка']);
    }

    public function saveData($flight_id, $user_id, $place, $status)
    {
        $this->place = $place;
        $this->status = $status;
        $this->flight_id = $flight_id;
        $this->user_id = $user_id;

        $this->save();
    }

    public function flightStatus($flight_id)
    {
        $flight = Flight::findOne($flight_id);
        $tickets = Ticket::find()->where(['flight_id' => $flight_id])->andWhere(['>', 'status', 0])->count();

        if($tickets >= $flight->amount_of_places) {
            $flight->status = 1;
            $flight->save();
        } else {
            $flight->status = 0;
            $flight->save();
        }
    }
}
